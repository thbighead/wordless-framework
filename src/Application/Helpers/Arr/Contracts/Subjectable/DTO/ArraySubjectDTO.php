<?php declare(strict_types=1);

namespace Wordless\Application\Helpers\Arr\Contracts\Subjectable\DTO;

use Doctrine\Inflector\Language;
use InvalidArgumentException;
use Wordless\Application\Helpers\Str;
use Wordless\Infrastructure\Helper\Contracts\Subjectable\DTO\SubjectDTO;

final class ArraySubjectDTO extends SubjectDTO
{
    public function __construct(array $subject)
    {
        parent::__construct($subject);
    }

    /**
     * @return array
     */
    public function getOriginalSubject(): array
    {
        return parent::getOriginalSubject();
    }

    /**
     * @return array
     */
    public function getSubject(): array
    {
        return parent::getSubject();
    }
}
