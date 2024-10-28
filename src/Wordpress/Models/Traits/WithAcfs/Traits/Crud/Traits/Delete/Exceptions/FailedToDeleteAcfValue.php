<?php

namespace Wordless\Wordpress\Models\Traits\WithAcfs\Traits\Crud\Traits\Delete\Exceptions;

use ErrorException;
use Throwable;

class FailedToDeleteAcfValue extends ErrorException
{
    public function __construct(
        public readonly string     $acf_reference,
        public readonly int|string $acf_from_id,
        ?Throwable                 $previous = null
    )
    {
        parent::__construct(
            "Failed to delete ACF referenced by '$this->acf_reference' for id $this->acf_from_id",
            previous: $previous
        );
    }
}
