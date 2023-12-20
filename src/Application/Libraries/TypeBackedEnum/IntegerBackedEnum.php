<?php

namespace Wordless\Application\Libraries\TypeBackedEnum;

use BackedEnum;

/**
 * @var int $value
 * @method static static from(int $value)
 * @method static static|null tryFrom(int $value)
 */
interface IntegerBackedEnum extends BackedEnum
{
}
