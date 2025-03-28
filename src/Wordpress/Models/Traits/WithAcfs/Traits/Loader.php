<?php declare(strict_types=1);

namespace Wordless\Wordpress\Models\Traits\WithAcfs\Traits;

use Wordless\Wordpress\Models\Traits\WithAcfs\Exceptions\InvalidAcfFunction;

trait Loader
{
    /** @noinspection PhpPrivateFieldCanBeLocalVariableInspection */
    private int|string $acf_from_id;
    private array $acfs = [];

    /**
     * @param int|string $from_id
     * @return void
     * @throws InvalidAcfFunction
     */
    private function loadAcfs(int|string $from_id): void
    {
        if (($acfs = $this->validateAcfFunction('get_fields')($this->acf_from_id = $from_id)) !== false) {
            $this->acfs = $acfs;
        }
    }
}
