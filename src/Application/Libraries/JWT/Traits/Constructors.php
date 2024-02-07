<?php declare(strict_types=1);

namespace Wordless\Application\Libraries\JWT\Traits;

use Lcobucci\JWT\Encoding\JoseEncoder;
use Lcobucci\JWT\Signer\CannotSignPayload;
use Lcobucci\JWT\Signer\Ecdsa\ConversionFailed;
use Lcobucci\JWT\Signer\InvalidKeyProvided;
use Lcobucci\JWT\Token\InvalidTokenStructure;
use Lcobucci\JWT\Token\Parser;
use Lcobucci\JWT\Token\Plain;
use Lcobucci\JWT\Token\RegisteredClaimGiven;
use Lcobucci\JWT\Token\UnsupportedHeaderFound;
use Wordless\Application\Helpers\Config\Contracts\Subjectable\DTO\ConfigSubjectDTO\Exceptions\EmptyConfigKey;
use Wordless\Application\Helpers\Config\Exceptions\InvalidConfigKey;
use Wordless\Application\Helpers\GetType;
use Wordless\Application\Helpers\ProjectPath\Exceptions\PathNotFoundException;
use Wordless\Application\Libraries\JWT\Enums\CryptoAlgorithm;
use Wordless\Application\Libraries\JWT\Exceptions\InvalidJwtCryptoAlgorithmId;

trait Constructors
{
    public static function constructorsDictionary(): array
    {
        return [
            1 => [
                GetType::STRING => '__constructParsingToken',
                GetType::ARRAY => '__constructWithPayloadUsingDefaultCrypto',
            ],
            2 => [
                GetType::ARRAY . CryptoAlgorithm::class => '__constructWithPayloadUsingCrypto',
            ]
        ];
    }

    /**
     * @param string $full_token
     * @return void
     * @throws InvalidTokenStructure
     * @throws UnsupportedHeaderFound
     */
    public function __constructParsingToken(string $full_token): void
    {
        /** @var Plain $parsedToken */
        $parsedToken = (new Parser(new JoseEncoder))->parse($full_token);

        $this->parsedToken = $parsedToken;
    }

    /**
     * @param array $payload
     * @return void
     * @throws CannotSignPayload
     * @throws ConversionFailed
     * @throws InvalidConfigKey
     * @throws InvalidJwtCryptoAlgorithmId
     * @throws InvalidKeyProvided
     * @throws PathNotFoundException
     * @throws RegisteredClaimGiven
     * @throws EmptyConfigKey
     */
    public function __constructWithPayloadUsingDefaultCrypto(array $payload): void
    {
        $this->buildJwt($payload);
    }

    /**
     * @param array $payload
     * @param CryptoAlgorithm $crypto_strategy
     * @return void
     * @throws CannotSignPayload
     * @throws ConversionFailed
     * @throws EmptyConfigKey
     * @throws InvalidConfigKey
     * @throws InvalidJwtCryptoAlgorithmId
     * @throws InvalidKeyProvided
     * @throws PathNotFoundException
     * @throws RegisteredClaimGiven
     */
    public function __constructWithPayloadUsingCrypto(array $payload, CryptoAlgorithm $crypto_strategy): void
    {
        $this->buildJwt($payload, $crypto_strategy);
    }
}
