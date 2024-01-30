<?php declare(strict_types=1);

namespace Wordless\Application\Helpers\Str\Contracts\Subjectable\DTO;

use Doctrine\Inflector\Language;
use InvalidArgumentException;
use Wordless\Application\Helpers\Str;
use Wordless\Application\Helpers\Str\Contracts\Subjectable\DTO\StringSubjectDTO\Traits\HelperMethods;
use Wordless\Application\Helpers\Str\Contracts\Subjectable\DTO\StringSubjectDTO\Traits\Internal;
use Wordless\Infrastructure\Helper\Contracts\Subjectable\DTO\SubjectDTO;

final class StringSubjectDTO extends SubjectDTO
{
    use HelperMethods;
    use Internal;
}
