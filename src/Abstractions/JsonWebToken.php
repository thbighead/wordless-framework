<?php

namespace Wordless\Abstractions;

use Lcobucci\JWT\Encoding\ChainedFormatter;
use Lcobucci\JWT\Encoding\JoseEncoder;
use Lcobucci\JWT\Signer;
use Lcobucci\JWT\Signer\Blake2b;
use Lcobucci\JWT\Signer\Eddsa;
use Lcobucci\JWT\Signer\Hmac\Sha256 as HmacSha256;
use Lcobucci\JWT\Signer\Hmac\Sha384 as HmacSha384;
use Lcobucci\JWT\Signer\Hmac\Sha512 as HmacSha512;
use Lcobucci\JWT\Signer\Key\InMemory;
use Lcobucci\JWT\Signer\Rsa\Sha256 as RsaSha256;
use Lcobucci\JWT\Signer\Rsa\Sha384 as RsaSha384;
use Lcobucci\JWT\Signer\Rsa\Sha512 as RsaSha512;
use Lcobucci\JWT\Token\Builder;
use Lcobucci\JWT\Token\Parser;
use Lcobucci\JWT\Token\Plain;
use Wordless\Contracts\IMultipleConstructors;
use Wordless\Contracts\MultipleConstructors;
use Wordless\Exceptions\InvalidConfigKey;
use Wordless\Exceptions\InvalidJwtCryptoAlgorithmId;
use Wordless\Exceptions\PathNotFoundException;
use Wordless\Helpers\Config;
use Wordless\Helpers\Crypto;
use Wordless\Helpers\GetType;

class JsonWebToken implements IMultipleConstructors
{
    use MultipleConstructors;

    public const CONFIG_DEFAULT_CRYPTO = 'default_crypto';
    public const CONFIG_SIGN_KEY = 'sign_key';
    public const ENVIRONMENT_SIGN_VARIABLE = 'JWT_BASE_64_SIGN_KEY';

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

    public function __constructParsingToken(string $full_token)
    {
        $this->parsedToken = (new Parser(new JoseEncoder))->parse($full_token);
    }

    /**
     * @param array $payload
     * @return void
     * @throws InvalidJwtCryptoAlgorithmId
     * @throws InvalidConfigKey
     * @throws PathNotFoundException
     */
    public function __constructWithPayloadUsingDefaultCrypto(array $payload)
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
    public function __constructWithPayloadUsingCrypto(array $payload, string $crypto_strategy)
    {
        $this->buildJwt($payload, $crypto_strategy);
    }

    public function __toString()
    {
        return $this->parsedToken->toString();
    }

    /**
     * @param string|null $key
     * @param $default
     * @return mixed|array|null
     */
    public function getDecodedHeader(?string $key = null, $default = null)
    {
        $header = $this->parsedToken->headers();

        if ($key === null) {
            return $header->all();
        }

        return $header->get($key, $default);
    }

    /**
     * @param string|null $key
     * @param $default
     * @return mixed|array|null
     */
    public function getDecodedPayload(?string $key = null, $default = null)
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
     * @param array $payload
     * @param string|null $crypto_strategy
     * @return void
     * @throws InvalidConfigKey
     * @throws InvalidJwtCryptoAlgorithmId
     * @throws PathNotFoundException
     */
    private function buildJwt(array $payload, ?string $crypto_strategy = null)
    {
        $builder = new Builder(new JoseEncoder, (new ChainedFormatter));

        foreach ($payload as $key => $value) {
            $builder->withClaim("$key", $value);
        }

        $this->parsedToken = $builder->getToken(
            $this->getCryptoAlgorithm($crypto_strategy ?? Config::get('jwt.' . self::CONFIG_DEFAULT_CRYPTO)),
            InMemory::base64Encoded(Config::get('jwt.' . self::CONFIG_SIGN_KEY))
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
            case Crypto::JWT_ASYMMETRIC_RSA_SSA_PKCS1_V1_5_SHA256:
                return new RsaSha256;
            case Crypto::JWT_ASYMMETRIC_RSA_SSA_PKCS1_V1_5_SHA384:
                return new RsaSha384;
            case Crypto::JWT_ASYMMETRIC_RSA_SSA_PKCS1_V1_5_SHA512:
                return new RsaSha512;
            case Crypto::JWT_ASYMMETRIC_ED_DSA:
                return new Eddsa;
            default:
                throw new InvalidJwtCryptoAlgorithmId($crypto_key);
        }
    }
}
