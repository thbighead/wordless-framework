<?php declare(strict_types=1);

namespace Wordless\Application\Libraries\JWT;

use Lcobucci\JWT\Encoding\ChainedFormatter;
use Lcobucci\JWT\Encoding\JoseEncoder;
use Lcobucci\JWT\Signer;
use Lcobucci\JWT\Signer\Blake2b;
use Lcobucci\JWT\Signer\CannotSignPayload;
use Lcobucci\JWT\Signer\Ecdsa\ConversionFailed;
use Lcobucci\JWT\Signer\Hmac\Sha256 as HmacSha256;
use Lcobucci\JWT\Signer\Hmac\Sha384 as HmacSha384;
use Lcobucci\JWT\Signer\Hmac\Sha512 as HmacSha512;
use Lcobucci\JWT\Signer\InvalidKeyProvided;
use Lcobucci\JWT\Signer\Key\InMemory;
use Lcobucci\JWT\Token\Builder;
use Lcobucci\JWT\Token\Plain;
use Lcobucci\JWT\Token\RegisteredClaimGiven;
use Lcobucci\JWT\Validation\Constraint\SignedWith;
use Lcobucci\JWT\Validation\Validator;
use Wordless\Application\Helpers\Config;
use Wordless\Application\Helpers\Config\Exceptions\InvalidConfigKey;
use Wordless\Application\Helpers\ProjectPath\Exceptions\PathNotFoundException;
use Wordless\Application\Helpers\Str;
use Wordless\Application\Libraries\JWT\Enums\CryptoAlgorithm;
use Wordless\Application\Libraries\JWT\Exceptions\InvalidJwtCryptoAlgorithmId;
use Wordless\Application\Libraries\JWT\Traits\Constructors;
use Wordless\Application\Libraries\PolymorphicConstructor\Contracts\IPolymorphicConstructor;
use Wordless\Application\Libraries\PolymorphicConstructor\Traits\PolymorphicConstructorGuesser;

/**
 * Using https://lcobucci-jwt.readthedocs.io/en/latest/
 */
class Token implements IPolymorphicConstructor
{
    use Constructors;
    use PolymorphicConstructorGuesser;

    final public const CONFIG_DEFAULT_CRYPTO = 'default_crypto';
    final public const CONFIG_SIGN_KEY = 'sign_key';
    final public const ENVIRONMENT_SIGN_VARIABLE = 'JWT_BASE_64_SIGN_KEY';
    final public const JWT_HEADER_ALGORITHM_KEY = 'alg';

    private Plain $parsedToken;

    public function __toString()
    {
        return $this->parsedToken->toString();
    }

    public function getDecodedHeader(?string $key = null, $default = null): array|bool|int|string|null
    {
        $header = $this->parsedToken->headers();

        if ($key === null) {
            return $header->all();
        }

        return $header->get($key, $default);
    }

    public function getDecodedPayload(?string $key = null, $default = null): array|bool|int|string|null
    {
        $payload = $this->parsedToken->claims();

        if ($key === null) {
            return $payload->all();
        }

        return $payload->get($key, $default);
    }

    public function getDecodedSignature(): string
    {
        return $this->parsedToken->signature()->hash();
    }

    public function getEncodedHeader(): string
    {
        return $this->parsedToken->headers()->toString();
    }

    public function getEncodedPayload(): string
    {
        return $this->parsedToken->claims()->toString();
    }

    public function getEncodedSignature(): string
    {
        return $this->parsedToken->signature()->toString();
    }

    /**
     * @return bool
     * @throws InvalidConfigKey
     * @throws InvalidJwtCryptoAlgorithmId
     * @throws PathNotFoundException
     */
    public function isValid(): bool
    {
        return (new Validator)->validate($this->parsedToken, new SignedWith(
            $this->getCryptoAlgorithm(
                $crypto_strategy = $this->getDecodedHeader(self::JWT_HEADER_ALGORITHM_KEY, '')
            ),
            $this->mountSignatureKey($crypto_strategy, Config::getOrFail('wordless.jwt.' . self::CONFIG_SIGN_KEY))
        ));
    }

    /**
     * @return void
     * @throws InvalidConfigKey
     * @throws InvalidJwtCryptoAlgorithmId
     * @throws PathNotFoundException
     */
    public function validateSignature(): void
    {
        (new Validator)->assert($this->parsedToken, new SignedWith(
            $this->getCryptoAlgorithm(
                $crypto_strategy = $this->getDecodedHeader(self::JWT_HEADER_ALGORITHM_KEY, '')
            ),
            $this->mountSignatureKey($crypto_strategy, Config::getOrFail('wordless.jwt.' . self::CONFIG_SIGN_KEY))
        ));
    }

    /**
     * @param array $payload
     * @param CryptoAlgorithm|null $crypto_strategy
     * @return void
     * @throws CannotSignPayload
     * @throws ConversionFailed
     * @throws InvalidConfigKey
     * @throws InvalidJwtCryptoAlgorithmId
     * @throws InvalidKeyProvided
     * @throws PathNotFoundException
     * @throws RegisteredClaimGiven
     */
    protected function buildJwt(array $payload, ?CryptoAlgorithm $crypto_strategy = null): void
    {
        $builder = new Builder(new JoseEncoder, (new ChainedFormatter));
        $crypto_strategy = $crypto_strategy ?? Config::getOrFail('wordless.jwt.' . self::CONFIG_DEFAULT_CRYPTO);

        foreach ($payload as $key => $value) {
            $builder->withClaim("$key", $value);
        }

        $this->parsedToken = $builder->getToken(
            $this->getCryptoAlgorithm($crypto_strategy),
            $this->mountSignatureKey($crypto_strategy, Config::getOrFail('wordless.jwt.' . self::CONFIG_SIGN_KEY))
        );
    }

    /**
     * @param CryptoAlgorithm|string $crypto_key
     * @return Signer
     * @throws InvalidJwtCryptoAlgorithmId
     */
    private function getCryptoAlgorithm(CryptoAlgorithm|string $crypto_key): Signer
    {
        if (is_string($crypto_key)) {
            $crypto_key = CryptoAlgorithm::tryFrom($crypto_key);
        }

        return match ($crypto_key) {
            CryptoAlgorithm::SYMMETRIC_HMAC_SHA256 => new HmacSha256,
            CryptoAlgorithm::SYMMETRIC_HMAC_SHA384 => new HmacSha384,
            CryptoAlgorithm::SYMMETRIC_HMAC_SHA512 => new HmacSha512,
            CryptoAlgorithm::SYMMETRIC_HMAC_BLAKE2B_HASH => new Blake2b,
            default => throw new InvalidJwtCryptoAlgorithmId($crypto_key ?? $crypto_key->value),
        };
    }

    /**
     * @param CryptoAlgorithm|string $crypto_key
     * @param string $key
     * @return InMemory
     * @throws InvalidJwtCryptoAlgorithmId
     */
    private function mountSignatureKey(CryptoAlgorithm|string $crypto_key, string $key): InMemory
    {
        $char_size_in_bits = 8;

        if (is_string($crypto_key)) {
            $crypto_key = CryptoAlgorithm::tryFrom($crypto_key);
        }

        $key = match ($crypto_key) {
            CryptoAlgorithm::SYMMETRIC_HMAC_SHA256, CryptoAlgorithm::SYMMETRIC_HMAC_BLAKE2B_HASH => Str::truncate(
                $key,
                256 / $char_size_in_bits
            ),
            CryptoAlgorithm::SYMMETRIC_HMAC_SHA384 => Str::truncate($key, 384 / $char_size_in_bits),
            CryptoAlgorithm::SYMMETRIC_HMAC_SHA512 => Str::truncate($key, 512 / $char_size_in_bits),
            default => throw new InvalidJwtCryptoAlgorithmId($crypto_key ?? $crypto_key->value),
        };

        return InMemory::plainText($key);
    }
}
