<?php declare(strict_types=1);

namespace Wordless\Wordpress\Models\Traits\WithAcfs\Traits\Crud\Traits;

use Wordless\Application\Helpers\Arr\Exceptions\FailedToParseArrayKey;
use Wordless\Wordpress\Models\Traits\WithAcfs\Exceptions\InvalidAcfFunction;

trait CreateOrUpdate
{
    /**
     * @param string $acf_reference
     * @param mixed $value
     * @return int|true
     * @throws InvalidAcfFunction
     */
    public function createOrUpdateAcfValue(string $acf_reference, mixed $value): int|true
    {
        $acf_reference_exploded = explode('.', $acf_reference);

        if (count($acf_reference_exploded) <= 1) {
            return $this->callUpdateField($acf_reference, $value);
        }

        $acf_reference = array_shift($acf_reference_exploded);

        for ($i = count($acf_reference_exploded) - 1; $i >= 0; $i--) {
            $value = [$acf_reference_exploded[$i] => $value];
        }

        if (($result = $this->callUpdateField($acf_reference, $value)) !== false) {
            $this->loadAcfs($this->acf_from_id);
        }

        return $result;
    }

    /**
     * @param array<string, mixed> $values
     * @return array<string, int|bool>
     * @throws InvalidAcfFunction
     */
    public function createOrUpdateAcfValues(array $values): array
    {
        $results = [];
        $should_load_acfs_again = false;

        foreach ($values as $base_acf_slug => $subfields_values) {
            $results[$base_acf_slug] = $this->callUpdateField($base_acf_slug, $subfields_values);
            $should_load_acfs_again = $should_load_acfs_again || $results[$base_acf_slug];
        }

        if ($should_load_acfs_again) {
            $this->loadAcfs($this->acf_from_id);
        }

        return $results;
    }

    /**
     * @param string $acf_selector
     * @param mixed $value
     * @return int|bool
     * @throws FailedToParseArrayKey
     * @throws InvalidAcfFunction
     */
    private function callUpdateField(string $acf_selector, mixed $value): int|bool
    {
        $result = $this->validateAcfFunction('update_field')($acf_selector, $value, $this->acf_from_id);

        if ($result === false && $this->getAcf($acf_selector) === $value) {
            return true;
        }

        return $result;
    }
}
