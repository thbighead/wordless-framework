<?php declare(strict_types=1);

namespace Wordless\Application\Libraries\TypeBackedEnum;

use BackedEnum;

/**
 * @var string $value
 * @method static static from(string $value)
 * @method static static|null tryFrom(string $value)
 */
interface StringBackedEnum extends BackedEnum
{
}
