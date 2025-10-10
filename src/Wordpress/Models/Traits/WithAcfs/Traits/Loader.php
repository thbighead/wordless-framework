<?php declare(strict_types=1);

namespace Wordless\Wordpress\Models\Traits\WithAcfs\Traits;

use Wordless\Wordpress\Models\Traits\WithAcfs\DTO\AcfFieldDTO;
use Wordless\Wordpress\Models\Traits\WithAcfs\Exceptions\InvalidAcfFunction;

trait Loader
{
    private int|string $acf_from_id;
    /** @var AcfFieldDTO[]|null $acfs */
    private ?array $acfs = null;

    abstract protected function mountAcfFromId(): int|string;

    /**
     * @return $this
     * @throws InvalidAcfFunction
     */
    public function reloadAcfs(): static
    {
        $this->loadAcfs();

        return $this;
    }

    private function getAcfFromId(): int|string
    {
        return $this->acf_from_id ?? $this->acf_from_id = $this->mountAcfFromId();
    }

    /**
     * @return void
     * @throws InvalidAcfFunction
     */
    private function loadAcfs(): void
    {
        if (($acfs = $this->validateAcfFunction('get_field_objects')($this->getAcfFromId())) !== false) {
            $this->acfs = array_map(function (array $acf_field_raw_data): AcfFieldDTO {
                return new AcfFieldDTO($acf_field_raw_data);
            }, $acfs);
        }
    }
}
