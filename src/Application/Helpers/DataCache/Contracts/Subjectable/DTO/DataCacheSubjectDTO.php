<?php declare(strict_types=1);

namespace Wordless\Application\Helpers\DataCache\Contracts\Subjectable\DTO;

use DateTimeInterface;
use Wordless\Application\Helpers\DataCache;
use Wordless\Application\Helpers\DataCache\Contracts\Subjectable\DTO\ArraySubjectDTO\Traits\Internal;
use Wordless\Application\Helpers\DataCache\Exceptions\FailedToSetTransient;
use Wordless\Application\Helpers\DataCache\Exceptions\InvalidTransientExpirationValue;
use Wordless\Application\Helpers\DataCache\Exceptions\TransientKeyIsTooLong;
use Wordless\Application\Helpers\DataCache\Exceptions\TransientKeyNotFound;
use Wordless\Application\Libraries\Carbon\Carbon;
use Wordless\Infrastructure\Helper\Contracts\Subjectable\DTO\SubjectDTO;

final class DataCacheSubjectDTO extends SubjectDTO
{
    use Internal;

    /**
     * @return void
     * @throws TransientKeyNotFound
     */
    public function delete(): void
    {
        DataCache::delete($this->subject);

        unset($this->value);
        $this->is_value_set = false;
    }

    public function get(mixed $default = null): mixed
    {
        if ($this->is_value_set) {
            return $this->value;
        }

        $this->is_value_set = true;

        return $this->value = DataCache::get($this->subject, $default);
    }

    /**
     * @param mixed $value
     * @param Carbon|DateTimeInterface|int|string $expires_in
     * @return void
     * @throws FailedToSetTransient
     * @throws InvalidTransientExpirationValue
     * @throws TransientKeyIsTooLong
     */
    public function set(mixed $value, Carbon|DateTimeInterface|int|string $expires_in = 0): void
    {
        DataCache::set($this->subject, $value, $expires_in);

        $this->value = $value;
        $this->is_value_set = true;
    }
}
