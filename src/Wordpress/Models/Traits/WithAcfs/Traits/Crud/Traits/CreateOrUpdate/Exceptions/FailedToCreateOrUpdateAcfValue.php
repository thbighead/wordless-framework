<?php

namespace Wordless\Wordpress\Models\Traits\WithAcfs\Traits\Crud\Traits\CreateOrUpdate\Exceptions;

use ErrorException;
use Throwable;

class FailedToCreateOrUpdateAcfValue extends ErrorException
{
 public function __construct(
     public readonly string $acf_reference,
     public readonly string $value,
     public readonly int|string $acf_from_id,
     ?Throwable $previous = null
 )
 {
     parent::__construct(
         "Failed to create or update ACF referenced by '$this->acf_reference' with value '$this->value' for id $this->acf_from_id",
         previous: $previous
     );
 }
}
