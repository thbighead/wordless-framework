<?php declare(strict_types=1);

namespace Wordless\Application\Helpers\Config\Contracts\Subjectable\DTO;

use Wordless\Application\Helpers\Config;
use Wordless\Application\Helpers\Config\Contracts\Subjectable\DTO\ConfigSubjectDTO\Exceptions\EmptyConfigKey;
use Wordless\Application\Helpers\Config\Contracts\Subjectable\DTO\ConfigSubjectDTO\Traits\Internal;
use Wordless\Application\Helpers\Config\Exceptions\InvalidConfigKey;
use Wordless\Application\Helpers\Config\Traits\Internal\Exceptions\FailedToLoadConfigFile;
use Wordless\Infrastructure\Helper\Contracts\Subjectable\DTO\SubjectDTO;

final class ConfigSubjectDTO extends SubjectDTO
{
    use Internal;

    /**
     * @param string|null $key
     * @param mixed|null $default
     * @return mixed
     * @throws FailedToLoadConfigFile
     */
    public function get(?string $key = null, mixed $default = null): mixed
    {
        return Config::get($this->getUpdatedSubject($key), $default);
    }

    /**
     * @param string $key
     * @return mixed
     * @throws FailedToLoadConfigFile
     * @throws InvalidConfigKey
     */
    public function getFresh(string $key): mixed
    {
        return Config::getFresh($this->getUpdatedSubject($key));
    }

    /**
     * @param string $key
     * @return mixed
     * @throws FailedToLoadConfigFile
     * @throws InvalidConfigKey
     */
    public function getOrFail(string $key): mixed
    {
        return Config::getOrFail($this->getUpdatedSubject($key));
    }

    /**
     * @param string $key
     * @param bool $override
     * @return $this
     * @throws EmptyConfigKey
     */
    public function ofKey(string $key, bool $override = false): self
    {
        if (empty($key)) {
            throw new EmptyConfigKey;
        }

        $this->subject = $override ? $key : "$this->subject.$key";

        return $this;
    }
}
