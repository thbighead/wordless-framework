<?php declare(strict_types=1);

namespace Wordless\Wordpress\Models\Traits\WithAcfs\Traits\Crud\Traits\Read\Exceptions;

use InvalidArgumentException;
use Throwable;
use Wordless\Infrastructure\Enums\ExceptionCode;

class AcfFieldNotFound extends InvalidArgumentException
{
 public function __construct(
     readonly public string $field_key,
     readonly public int|string $acf_from_id,
     ?Throwable $previous = null
 )
 {
     parent::__construct(
         "Failed to find ACF field by reference '$this->field_key' for $this->acf_from_id.",
         ExceptionCode::development_error->value,
         $previous
     );
 }
}
