<?php

namespace Wordless\Exceptions;

use InvalidArgumentException;
use Throwable;
use Wordless\Helpers\GetType;

class ValidationRulesKeyMustBeString extends InvalidArgumentException
{
    /** @var mixed $invalid_field */
    private $invalid_field;

    public function __construct($invalid_field, Throwable $previous = null)
    {
        $this->invalid_field = $invalid_field;

        parent::__construct($this->mountMessage(), 0, $previous);
    }

    /**
     * @return mixed
     */
    public function getInvalidField()
    {
        return $this->invalid_field;
    }

    private function mountMessage(): string
    {
        $message = 'A validation field key must be a string, but it is '
            . GetType::of($this->getInvalidField())
            . ' instead';

        return GetType::isStringable($this->getInvalidField()) ?
            "$message: '{$this->getInvalidField()}'" :
            "$message.";
    }
}
