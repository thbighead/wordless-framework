<?php declare(strict_types=1);

namespace Wordless\Wordpress\Models\Traits\WithAcfs\Traits;

use Wordless\Wordpress\Models\Traits\WithAcfs\Exceptions\InvalidAcfFunction;

trait Loader
{
    private int|string $acf_from_id;
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
        if (($acfs = $this->validateAcfFunction('get_fields')($this->getAcfFromId())) !== false) {
            $this->acfs = $acfs;
        }
    }
}
