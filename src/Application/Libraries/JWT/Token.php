<?php declare(strict_types=1);

namespace Wordless\Application\Libraries\JWT;

use Lcobucci\JWT\Encoding\CannotEncodeContent;
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
use Lcobucci\JWT\Validation\NoConstraintsGiven;
use Lcobucci\JWT\Validation\RequiredConstraintsViolated;
use Lcobucci\JWT\Validation\Validator;
use Wordless\Application\Helpers\Config;
use Wordless\Application\Helpers\Config\Contracts\Subjectable\DTO\ConfigSubjectDTO;
use Wordless\Application\Helpers\Config\Contracts\Subjectable\DTO\ConfigSubjectDTO\Exceptions\EmptyConfigKey;
use Wordless\Application\Helpers\Config\Exceptions\InvalidConfigKey;
use Wordless\Application\Helpers\Config\Traits\Internal\Exceptions\FailedToLoadConfigFile;
use Wordless\Application\Helpers\ProjectPath\Exceptions\PathNotFoundException;
use Wordless\Application\Helpers\Str;
use Wordless\Application\Libraries\JWT\Enums\CryptoAlgorithm;
use Wordless\Application\Libraries\JWT\Exceptions\InvalidJwtCryptoAlgorithmId;
use Wordless\Application\Libraries\JWT\Token\Exceptions\FailedToBuildJwt;
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
    final public const CONFIG_KEY = 'jwt';
    final public const CONFIG_SIGN_KEY = 'sign_key';
    final public const ENVIRONMENT_SIGN_VARIABLE = 'JWT_BASE_64_SIGN_KEY';
    final public const JWT_HEADER_ALGORITHM_KEY = 'alg';

    private ConfigSubjectDTO $config;
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
     * @throws EmptyConfigKey
     * @throws FailedToLoadConfigFile
     * @throws InvalidConfigKey
     * @throws InvalidJwtCryptoAlgorithmId
     * @throws NoConstraintsGiven
     */
    public function isValid(): bool
    {
        return (new Validator)->validate($this->parsedToken, new SignedWith(
            $this->getCryptoAlgorithm(
                $crypto_strategy = $this->getDecodedHeader(self::JWT_HEADER_ALGORITHM_KEY, '')
            ),
            $this->mountSignatureKey($crypto_strategy, $this->getConfig()->getOrFail(self::CONFIG_SIGN_KEY))
        ));
    }

    /**
     * @return void
     * @throws EmptyConfigKey
     * @throws FailedToLoadConfigFile
     * @throws InvalidConfigKey
     * @throws InvalidJwtCryptoAlgorithmId
     * @throws NoConstraintsGiven
     * @throws RequiredConstraintsViolated
     */
    public function validateSignature(): void
    {
        (new Validator)->assert($this->parsedToken, new SignedWith(
            $this->getCryptoAlgorithm(
                $crypto_strategy = $this->getDecodedHeader(self::JWT_HEADER_ALGORITHM_KEY, '')
            ),
            $this->mountSignatureKey($crypto_strategy, $this->getConfig()->getOrFail(self::CONFIG_SIGN_KEY))
        ));
    }

    /**
     * @param array $payload
     * @param CryptoAlgorithm|null $crypto_strategy
     * @return void
     * @throws FailedToBuildJwt
     */
    protected function buildJwt(array $payload, ?CryptoAlgorithm $crypto_strategy = null): void
    {
        try {
            $builder = new Builder(new JoseEncoder, (new ChainedFormatter));
            $crypto_strategy = $crypto_strategy ?? $this->getConfig()->getOrFail(self::CONFIG_DEFAULT_CRYPTO);

            foreach ($payload as $key => $value) {
                $builder = $builder->withClaim("$key", $value);
            }

            $this->parsedToken = $builder->getToken(
                $this->getCryptoAlgorithm($crypto_strategy),
                $this->mountSignatureKey($crypto_strategy, $this->getConfig()->getOrFail(self::CONFIG_SIGN_KEY))
            );
        } catch (CannotEncodeContent
        |CannotSignPayload
        |ConversionFailed
        |EmptyConfigKey
        |FailedToLoadConfigFile
        |InvalidConfigKey
        |InvalidJwtCryptoAlgorithmId
        |InvalidKeyProvided
        |RegisteredClaimGiven $exception) {
            throw new FailedToBuildJwt($payload, $crypto_strategy, $exception);
        }
    }

    /**
     * @return ConfigSubjectDTO
     * @throws EmptyConfigKey
     */
    private function getConfig(): ConfigSubjectDTO
    {
        return $this->config ?? $this->config = Config::wordless()
            ->ofKey(self::CONFIG_KEY);
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
            CryptoAlgorithm::symmetric_hmac_sha256 => new HmacSha256,
            CryptoAlgorithm::symmetric_hmac_sha384 => new HmacSha384,
            CryptoAlgorithm::symmetric_hmac_sha512 => new HmacSha512,
            CryptoAlgorithm::symmetric_hmac_blake2b_hash => new Blake2b,
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
            CryptoAlgorithm::symmetric_hmac_sha256, CryptoAlgorithm::symmetric_hmac_blake2b_hash => Str::truncate(
                $key,
                256 / $char_size_in_bits
            ),
            CryptoAlgorithm::symmetric_hmac_sha384 => Str::truncate($key, 384 / $char_size_in_bits),
            CryptoAlgorithm::symmetric_hmac_sha512 => Str::truncate($key, 512 / $char_size_in_bits),
            default => throw new InvalidJwtCryptoAlgorithmId($crypto_key ?? $crypto_key->value),
        };

        return InMemory::plainText($key);
    }
}
