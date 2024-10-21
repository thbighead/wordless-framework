<?php

namespace Wordless\Wordpress\Models\Traits\WithAcfs\Traits\Crud\Traits;

use Wordless\Wordpress\Models\Traits\WithAcfs\Exceptions\InvalidAcfFunction;
use Wordless\Wordpress\Models\Traits\WithAcfs\Traits\Crud\Traits\CreateOrUpdate\Exceptions\FailedToCreateOrUpdateAcfValue;

trait CreateOrUpdate
{
    /**
     * @param string $acf_reference
     * @param string $value
     * @return int|true
     * @throws FailedToCreateOrUpdateAcfValue
     * @throws InvalidAcfFunction
     */
    public function createOrUpdateAcfValue(string $acf_reference, string $value): int|true
    {
        if (($result = $this->validateAcfFunction('update_field')(
                $acf_reference,
                $value,
                $this->acf_from_id
            )) === false) {
            throw new FailedToCreateOrUpdateAcfValue($acf_reference, $value, $this->acf_from_id);
        }

        $this->loadAcfs($this->acf_from_id);

        return $result;
    }
}
