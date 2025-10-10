<?php declare(strict_types=1);

namespace Wordless\Wordpress\Models\Traits\WithAcfs\Traits\Crud\Traits;

use Wordless\Application\Helpers\Str;
use Wordless\Wordpress\Models\Traits\WithAcfs\Exceptions\InvalidAcfFunction;
use Wordless\Wordpress\Models\Traits\WithAcfs\Traits\Crud\Traits\Delete\Exceptions\FailedToDeleteAcfValue;

trait Delete
{
    /**
     * @param string $acf_reference
     * @return int|true
     * @throws FailedToDeleteAcfValue
     * @throws InvalidAcfFunction
     */
    public function deleteAcfValue(string $acf_reference): int|true
    {
        if (($result = $this->validateAcfFunction('delete_field')(
                $acf_reference = Str::replace($acf_reference, '.', '_'),
                $this->getAcfFromId()
            )) === false) {
            throw new FailedToDeleteAcfValue($acf_reference, $this->getAcfFromId());
        }

        $this->loadAcfs();

        return $result;
    }
}
