<?php declare(strict_types=1);

namespace Wordless\Wordpress\Models\MenuItem\DTO;

use Wordless\Wordpress\Models\MenuItem\Enums\Type;

readonly class TypeDTO
{
    public function __construct(
        public Type   $type,
        public string $label
    )
    {
    }
}
