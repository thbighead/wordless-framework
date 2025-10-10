<?php declare(strict_types=1);

namespace Wordless\Wordpress\Models\Traits\WithAcfs\Traits\Crud\Traits;

use Wordless\Application\Helpers\Arr;
use Wordless\Application\Helpers\Arr\Exceptions\FailedToFindArrayKey;
use Wordless\Application\Helpers\Arr\Exceptions\FailedToParseArrayKey;
use Wordless\Wordpress\Models\Traits\WithAcfs\Exceptions\InvalidAcfFunction;
use Wordless\Wordpress\Models\Traits\WithAcfs\Traits\Crud\Traits\Read\Exceptions\AcfFieldNotFound;

trait Read
{
    /**
     * @param string $field_key
     * @param mixed|null $default
     * @return mixed
     * @throws FailedToParseArrayKey
     * @throws InvalidAcfFunction
     */
    public function getAcf(string $field_key, mixed $default = null): mixed
    {
        try {
            return $this->getAcfOrFail($field_key);
        } catch (AcfFieldNotFound $exception) {
            if (($previousException = $exception->getPrevious()) instanceof FailedToParseArrayKey) {
                /** @var FailedToParseArrayKey $previousException */
                throw $previousException;
            }

            return $default;
        }
    }

    /**
     * @param string $field_key
     * @return mixed
     * @throws AcfFieldNotFound
     * @throws InvalidAcfFunction
     */
    public function getAcfOrFail(string $field_key): mixed
    {
        try {
            return Arr::getOrFail($this->getAcfs(), $field_key);
        } catch (FailedToFindArrayKey|FailedToParseArrayKey $exception) {
            throw new AcfFieldNotFound($field_key, $this->getAcfFromId(), $exception);
        }
    }

    /**
     * @return array<string, mixed>
     * @throws InvalidAcfFunction
     */
    public function getAcfs(): array
    {
        if (!is_array($this->acfs)) {
            $this->loadAcfs();
        }

        return $this->acfs;
    }
}
