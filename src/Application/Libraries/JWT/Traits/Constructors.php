<?php declare(strict_types=1);

namespace Wordless\Application\Libraries\JWT\Traits;

use Lcobucci\JWT\Encoding\CannotDecodeContent;
use Lcobucci\JWT\Encoding\JoseEncoder;
use Lcobucci\JWT\Token\InvalidTokenStructure;
use Lcobucci\JWT\Token\Parser;
use Lcobucci\JWT\Token\Plain;
use Lcobucci\JWT\Token\UnsupportedHeaderFound;
use Wordless\Application\Helpers\GetType;
use Wordless\Application\Libraries\JWT\Enums\CryptoAlgorithm;
use Wordless\Application\Libraries\JWT\Token\Exceptions\FailedToBuildJwt;
use Wordless\Application\Libraries\JWT\Traits\Constructors\Exceptions\FailedToParseStringFullToken;

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
     * @throws FailedToParseStringFullToken
     */
    public function __constructParsingToken(string $full_token): void
    {
        /** @var Plain $parsedToken */
        try {
            $parsedToken = (new Parser(new JoseEncoder))->parse($full_token);
        } catch (CannotDecodeContent|InvalidTokenStructure|UnsupportedHeaderFound $exception) {
            throw new FailedToParseStringFullToken($full_token, $exception);
        }

        $this->parsedToken = $parsedToken;
    }

    /**
     * @param array $payload
     * @return void
     * @throws FailedToBuildJwt
     */
    public function __constructWithPayloadUsingDefaultCrypto(array $payload): void
    {
        $this->buildJwt($payload);
    }

    /**
     * @param array $payload
     * @param CryptoAlgorithm $crypto_strategy
     * @return void
     * @throws FailedToBuildJwt
     */
    public function __constructWithPayloadUsingCrypto(array $payload, CryptoAlgorithm $crypto_strategy): void
    {
        $this->buildJwt($payload, $crypto_strategy);
    }
}
