<?php

namespace Wordless\Exceptions;

use InvalidArgumentException;
use Symfony\Component\Validator\Constraint;
use Throwable;
use Wordless\Helpers\GetType;

class ValidationRuleMustBeConstraint extends InvalidArgumentException
{
    /** @var mixed $invalidRule */
    private $invalidRule;

    public function __construct($invalidRule, Throwable $previous = null)
    {
        $this->invalidRule = $invalidRule;

        parent::__construct(
            'Validation Rule must be an instance of'
            . Constraint::class
            . ', but it is '
            . GetType::of($this->getInvalidRule())
            . ' instead.',
            0,
            $previous
        );
    }

    /**
     * @return mixed
     */
    public function getInvalidRule()
    {
        return $this->invalidRule;
    }
}
