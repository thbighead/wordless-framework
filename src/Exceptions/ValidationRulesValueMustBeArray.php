<?php

namespace Wordless\Exceptions;

use InvalidArgumentException;
use Throwable;
use Wordless\Helpers\GetType;

class ValidationRulesValueMustBeArray extends InvalidArgumentException
{
    /** @var mixed $invalid_rules */
    private $invalid_rules;

    public function __construct($invalid_rules, Throwable $previous = null)
    {
        $this->invalid_rules = $invalid_rules;

        parent::__construct(
            'Validation Rules must be an array, but it is '
            . GetType::of($this->getInvalidRules())
            . ' instead.',
            0,
            $previous
        );
    }

    /**
     * @return mixed
     */
    public function getInvalidRules()
    {
        return $this->invalid_rules;
    }
}
