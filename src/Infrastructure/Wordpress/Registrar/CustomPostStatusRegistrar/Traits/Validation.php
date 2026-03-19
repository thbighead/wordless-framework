<?php declare(strict_types=1);

namespace Wordless\Infrastructure\Wordpress\Registrar\CustomPostStatusRegistrar\Traits;

use Wordless\Application\Helpers\Reserved;
use Wordless\Infrastructure\Wordpress\Registrar\CustomPostStatusRegistrar\Traits\Validation\Exceptions\ReservedCustomPostStatusKey;

trait Validation
{
    /**
     * @return void
     * @throws ReservedCustomPostStatusKey
     */
    private static function validateName(): void
    {
        if (Reserved::isPostStatusUsedByWordPress($name_key = static::NAME)) {
            throw new ReservedCustomPostStatusKey($name_key);
        }
    }
}
