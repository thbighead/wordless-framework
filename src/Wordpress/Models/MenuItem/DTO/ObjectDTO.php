<?php declare(strict_types=1);

namespace Wordless\Wordpress\Models\MenuItem\DTO;

readonly class ObjectDTO
{
    public ?int $id;

    public function __construct(
        public string $type,
        ?string $id
    )
    {
        $this->id = $this->type === 'custom' || $id === null ? null : (int)$id;
    }
}
