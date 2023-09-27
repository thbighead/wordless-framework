<?php

namespace Wordless\Infrastructure\Wordpress\CustomPost\Traits\Register\DTO;

use Wordless\Application\Libraries\DesignPattern\DataTransferObject\ArrayDTO;
use Wordless\Infrastructure\Wordpress\CustomPost\Traits\Register\DTO\FieldsSupportedArrayDTO\Enums\CustomPostTypeFieldSupported;
use Wordless\Infrastructure\Wordpress\CustomPost\Traits\Register\DTO\FieldsSupportedArrayDTO\Traits\DataSetter;

final class FieldsSupportedArrayDTO extends ArrayDTO
{
    use DataSetter;

    /** @var CustomPostTypeFieldSupported[] $data */
    protected ?array $data = [];
    /** @var string[] $supported */
    private array $supported;

    /**
     * @return string[]
     */
    public function getSupported(): array
    {
        if (isset($this->supported)) {
            return $this->supported;
        }

        $this->supported = [];

        foreach ($this->data as $fieldSupported) {
            /** @var CustomPostTypeFieldSupported $fieldSupported */
            $this->supported[] = $fieldSupported->value;
        }

        return $this->supported;
    }
}
