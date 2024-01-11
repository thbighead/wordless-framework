<?php declare(strict_types=1);

namespace Wordless\Application\Libraries\JWT\Traits;

use Lcobucci\JWT\Encoding\JoseEncoder;
use Lcobucci\JWT\Token\Parser;
use Lcobucci\JWT\Token\Plain;
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
     * @param CryptoAlgorithm $crypto_strategy
     * @return void
     * @throws InvalidConfigKey
     * @throws InvalidJwtCryptoAlgorithmId
     * @throws PathNotFoundException
     */
    public function __constructWithPayloadUsingCrypto(array $payload, CryptoAlgorithm $crypto_strategy): void
    {
        $this->buildJwt($payload, $crypto_strategy->value);
    }
}
