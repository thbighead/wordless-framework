<?php

namespace Wordless\Application;

use Lcobucci\JWT\Encoding\ChainedFormatter;
use Lcobucci\JWT\Encoding\JoseEncoder;
use Lcobucci\JWT\Signer;
use Lcobucci\JWT\Signer\Blake2b;
use Lcobucci\JWT\Signer\Hmac\Sha256 as HmacSha256;
use Lcobucci\JWT\Signer\Hmac\Sha384 as HmacSha384;
use Lcobucci\JWT\Signer\Hmac\Sha512 as HmacSha512;
use Lcobucci\JWT\Signer\Key\InMemory;
use Lcobucci\JWT\Token\Builder;
use Lcobucci\JWT\Token\Parser;
use Lcobucci\JWT\Token\Plain;
use Lcobucci\JWT\Validation\Constraint\SignedWith;
use Lcobucci\JWT\Validation\Validator;
use Wordless\Application\Helpers\Config;
use Wordless\Application\Helpers\Crypto;
use Wordless\Application\Helpers\GetType;
use Wordless\Application\Helpers\Str;
use Wordless\Contracts\MultipleConstructors\IMultipleConstructors;
use Wordless\Contracts\MultipleConstructors\Traits\MultipleConstructorsGuesser;
use Wordless\Exceptions\InvalidConfigKey;
use Wordless\Exceptions\InvalidJwtCryptoAlgorithmId;
use Wordless\Exceptions\PathNotFoundException;

/**
 * Using https://lcobucci-jwt.readthedocs.io/en/latest/
 */
class JsonWebToken implements IMultipleConstructors
{
    use MultipleConstructorsGuesser;

    public const CONFIG_DEFAULT_CRYPTO = 'default_crypto';
    public const CONFIG_SIGN_KEY = 'sign_key';
    public const ENVIRONMENT_SIGN_VARIABLE = 'JWT_BASE_64_SIGN_KEY';
    public const JWT_HEADER_ALGORITHM_KEY = 'alg';

    private Plain $parsedToken;

    public static function constructorsDictionary(): array
    {
        return [
            1 => [
                GetType::STRING => '__constructParsingToken',
                GetType::ARRAY => '__constructWithPayloadUsingDefaultCrypto',
            ],
            2 => [
                GetType::ARRAY . GetType::STRING => '__constructWithPayloadUsingCrypto',
            ]
        ];
    }

    public function __constructParsingToken(string $full_token): void
    {
        /** @var Plain $parsedToken */
        $parsedToken = (new Parser(new JoseEncoder))->parse($full_token);

        $this->parsedToken = $parsedToken;
    }

    /**
     * @param array $payload
     * @return void
     * @throws InvalidJwtCryptoAlgorithmId
     * @throws InvalidConfigKey
     * @throws PathNotFoundException
     */
    public function __constructWithPayloadUsingDefaultCrypto(array $payload): void
    {
        $this->buildJwt($payload);
    }

    /**
     * @param array $payload
     * @param string $crypto_strategy
     * @return void
     * @throws InvalidConfigKey
     * @throws InvalidJwtCryptoAlgorithmId
     * @throws PathNotFoundException
     */
    public function __constructWithPayloadUsingCrypto(array $payload, string $crypto_strategy): void
    {
        $this->buildJwt($payload, $crypto_strategy);
    }

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
            $this->mountSignatureKey($crypto_strategy, Config::get('jwt.' . self::CONFIG_SIGN_KEY))
        ));
    }

    /**
     * @return void
     * @throws InvalidConfigKey
     * @throws InvalidJwtCryptoAlgorithmId
     * @throws PathNotFoundException
     */
    public function validateSignature()
    {
        (new Validator)->assert($this->parsedToken, new SignedWith(
            $this->getCryptoAlgorithm(
                $crypto_strategy = $this->getDecodedHeader(self::JWT_HEADER_ALGORITHM_KEY, '')
            ),
            $this->mountSignatureKey($crypto_strategy, Config::get('jwt.' . self::CONFIG_SIGN_KEY))
        ));
    }

    /**
     * @param array $payload
     * @param string|null $crypto_strategy
     * @return void
     * @throws InvalidConfigKey
     * @throws InvalidJwtCryptoAlgorithmId
     * @throws PathNotFoundException
     */
    protected function buildJwt(array $payload, ?string $crypto_strategy = null)
    {
        $builder = new Builder(new JoseEncoder, (new ChainedFormatter));
        $crypto_strategy = $crypto_strategy ?? Config::get('jwt.' . self::CONFIG_DEFAULT_CRYPTO);

        foreach ($payload as $key => $value) {
            $builder->withClaim("$key", $value);
        }

        $this->parsedToken = $builder->getToken(
            $this->getCryptoAlgorithm($crypto_strategy),
            $this->mountSignatureKey($crypto_strategy, Config::get('jwt.' . self::CONFIG_SIGN_KEY))
        );
    }

    /**
     * @param string $crypto_key (use from Wordless\Helpers\Crypto to avoid mistakes)
     * @throws InvalidJwtCryptoAlgorithmId
     */
    private function getCryptoAlgorithm(string $crypto_key): Signer
    {
        switch ($crypto_key) {
            case Crypto::JWT_SYMMETRIC_HMAC_SHA256:
                return new HmacSha256;
            case Crypto::JWT_SYMMETRIC_HMAC_SHA384:
                return new HmacSha384;
            case Crypto::JWT_SYMMETRIC_HMAC_SHA512:
                return new HmacSha512;
            case Crypto::JWT_SYMMETRIC_HMAC_BLAKE2B_HASH:
                return new Blake2b;
            default:
                throw new InvalidJwtCryptoAlgorithmId($crypto_key);
        }
    }

    /**
     * @param string $crypto_key
     * @param string $key
     * @return InMemory
     * @throws InvalidJwtCryptoAlgorithmId
     */
    private function mountSignatureKey(string $crypto_key, string $key): InMemory
    {
        $char_size_in_bits = 8;

        switch ($crypto_key) {
            case Crypto::JWT_SYMMETRIC_HMAC_SHA256:
            case Crypto::JWT_SYMMETRIC_HMAC_BLAKE2B_HASH:
                $key = Str::truncate($key, 256 / $char_size_in_bits);
                break;
            case Crypto::JWT_SYMMETRIC_HMAC_SHA384:
                $key = Str::truncate($key, 384 / $char_size_in_bits);
                break;
            case Crypto::JWT_SYMMETRIC_HMAC_SHA512:
                $key = Str::truncate($key, 512 / $char_size_in_bits);
                break;
            default:
                throw new InvalidJwtCryptoAlgorithmId($crypto_key);
        }

        return InMemory::plainText($key);
    }
}
