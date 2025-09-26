<?php declare(strict_types=1);

namespace Wordless\Wordpress\QueryBuilder\UserQueryBuilder\Traits\Resolver\Traits;

use Wordless\Wordpress\QueryBuilder\UserQueryBuilder\Traits\Resolver\Enums\ReturnField;

trait ArgumentsFixer
{
    private function fixArguments(): void
    {
        $this->fixFieldsArgument();
    }

    private function fixFieldsArgument(): void
    {
        if (isset($this->arguments[self::KEY_FIELDS])) {
            /** @var ReturnField $field */
            foreach ($this->arguments[self::KEY_FIELDS] as &$field) {
                $field = $field->value;
            }
        }
    }
}
