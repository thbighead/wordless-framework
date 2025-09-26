<?php declare(strict_types=1);

namespace Wordless\Wordpress\QueryBuilder\UserQueryBuilder\Traits;

use Wordless\Application\Helpers\Arr;

trait Capability
{
    public function whereCapability(string $capability): static
    {
        $this->arguments['capability'][] = $capability;

        return $this;
    }

    public function whereCapabilityIn(string $capability, string ...$capabilities): static
    {
        $this->arguments['capability__in'] = Arr::prepend($capabilities, $capability);

        return $this;
    }

    public function whereCapabilityNotIn(string $capability, string ...$capabilities): static
    {
        $this->arguments['capability__not_in'] = Arr::prepend($capabilities, $capability);

        return $this;
    }
}
