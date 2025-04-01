<?php declare(strict_types=1);

namespace Wordless\Wordpress\Models\MenuItem\DTO;

readonly class LinkDTO
{
    public function __construct(
        public string  $url,
        public bool    $target_blank,
        public ?string $title_attribute
    )
    {
    }
}
