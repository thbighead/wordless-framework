<?php declare(strict_types=1);

namespace Wordless\Application\Helpers\Crypto\Exceptions;

use InvalidArgumentException;
use Throwable;
use Wordless\Infrastructure\Enums\ExceptionCode;

class DecryptionFailed extends InvalidArgumentException
{
    public function __construct(public readonly string $string_to_decrypt, ?Throwable $previous = null)
    {
        parent::__construct($this->mountMessage(), ExceptionCode::intentional_interrupt->value, $previous);
    }

    private function mountMessage(): string
    {
        $message = "Could not decrypt $this->string_to_decrypt.";

        if ($this->getPrevious() !== null) {
            $message .= " {$this->getPrevious()->getMessage()}";
        }

        return $message;
    }
}
