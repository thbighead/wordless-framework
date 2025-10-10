<?php declare(strict_types=1);

namespace Wordless\Wordpress\Models\Traits\WithAcfs\DTO;

use Wordless\Application\Helpers\Str;

readonly class AcfFieldDTO
{
    public string $instructions;
    public string $key;
    public string $label;
    public string $name;
    public string $parent_key;
    public bool $required;
    public string $type;
    public mixed $value;

    public function __construct(public array $acf_field_raw_data)
    {
        $this->instructions = $this->acf_field_raw_data['instructions'] ?? '';
        $this->key = $this->acf_field_raw_data['key'];
        $this->label = $this->acf_field_raw_data['label'];
        $this->name = $this->acf_field_raw_data['name'];
        $this->parent_key = $this->acf_field_raw_data['parent_key'];
        $this->required = (bool)($this->acf_field_raw_data['required'] ?? false);
        $this->type = $this->acf_field_raw_data['type'];
        $this->value = $this->acf_field_raw_data['value'];
    }

    public function isParentGroup(): bool
    {
        return Str::beginsWith($this->parent_key, 'group_');
    }
}
