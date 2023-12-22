<?php

namespace Wordless\Application\Helpers\Str\Contracts\Subjectable\DTO;

use Doctrine\Inflector\Language;
use Wordless\Application\Helpers\Str;
use Wordless\Infrastructure\Helper\Contracts\Subjectable\DTO\SubjectDTO;

/**
 * @property string|Str $helper_class_namespace
 */
final class StringSubjectDTO extends SubjectDTO
{
    public function __construct(string $subject)
    {
        parent::__construct($subject, Str::class);
    }

    /**
     * @return string
     */
    public function getOriginalSubject(): string
    {
        return parent::getOriginalSubject();
    }

    /**
     * @return string
     */
    public function getSubject(): string
    {
        return parent::getSubject();
    }

    public function __toString(): string
    {
        return $this->getSubject();
    }

    public function after(string $delimiter): StringSubjectDTO
    {
        $this->subject = $this->helper_class_namespace::after($this->subject, $delimiter);

        return $this;
    }

    public function afterLast(string $delimiter): StringSubjectDTO
    {
        $this->subject = $this->helper_class_namespace::afterLast($this->subject, $delimiter);

        return $this;
    }

    public function before(string $delimiter): StringSubjectDTO
    {
        $this->subject = $this->helper_class_namespace::before($this->subject, $delimiter);

        return $this;
    }

    public function beforeLast(string $delimiter): StringSubjectDTO
    {
        $this->subject = $this->helper_class_namespace::beforeLast($this->subject, $delimiter);

        return $this;
    }

    public function beginsWith(string $substring): bool
    {
        return $this->helper_class_namespace::beginsWith($this->subject, $substring);
    }

    public function between(string $prefix, string $suffix): StringSubjectDTO
    {
        $this->subject = $this->helper_class_namespace::between($this->subject, $prefix, $suffix);

        return $this;
    }

    public function camelCase(): StringSubjectDTO
    {
        $this->subject = $this->helper_class_namespace::camelCase($this->subject);

        return $this;
    }

    /**
     * @param string|string[] $needles
     * @param bool $any
     * @return bool
     */
    public function contains(string|array $needles, bool $any = true): bool
    {
        return $this->helper_class_namespace::contains( $this->subject, $needles, $any);
    }

    public function countSubstring(string $substring): int
    {
        return $this->helper_class_namespace::countSubstring( $this->subject, $substring);
    }

    public function endsWith(string $substring): bool
    {
        return $this->helper_class_namespace::endsWith($this->subject, $substring);
    }

    public function finishWith(string $finish_with): StringSubjectDTO
    {
        $this->subject = $this->helper_class_namespace::finishWith($this->subject, $finish_with);

        return $this;
    }

    public function isSurroundedBy(string $prefix, string $suffix): bool
    {
        return $this->helper_class_namespace::isSurroundedBy($this->subject, $prefix, $suffix);
    }

    public function kebabCase(): StringSubjectDTO
    {
        $this->subject = $this->helper_class_namespace::kebabCase($this->subject);

        return $this;
    }

    public function limitWords(int $max_words = Str::DEFAULT_LIMIT_WORDS, string $limit_marker = '...'): StringSubjectDTO
    {
        $this->subject = $this->helper_class_namespace::limitWords($this->subject, $max_words, $limit_marker);

        return $this;
    }

    public function lower(): StringSubjectDTO
    {
        $this->subject = $this->helper_class_namespace::lower($this->subject);

        return $this;
    }

    public function pascalCase(): StringSubjectDTO
    {
        $this->subject = $this->helper_class_namespace::pascalCase($this->subject);

        return $this;
    }

    public function plural(string $language = Language::ENGLISH): StringSubjectDTO
    {
        $this->subject = $this->helper_class_namespace::plural($this->subject, $language);

        return $this;
    }

    public function removeSuffix(string $suffix): StringSubjectDTO
    {
        $this->subject = $this->helper_class_namespace::removeSuffix($this->subject, $suffix);

        return $this;
    }

    /**
     * @param string|string[] $search
     * @param string|string[] $replace
     * @return $this
     */
    public function replace(string|array $search, string|array $replace): StringSubjectDTO
    {
        $this->subject = $this->helper_class_namespace::replace($this->subject, $search, $replace);

        return $this;
    }

    public function singular(string $language = Language::ENGLISH): StringSubjectDTO
    {
        $this->subject = $this->helper_class_namespace::singular($this->subject, $language);

        return $this;
    }

    public function slugCase(): StringSubjectDTO
    {
        $this->subject = $this->helper_class_namespace::slugCase($this->subject);

        return $this;
    }

    public function snakeCase(string $delimiter = '_'): StringSubjectDTO
    {
        $this->subject = $this->helper_class_namespace::snakeCase($this->subject, $delimiter);

        return $this;
    }

    public function startWith(string $start_with): StringSubjectDTO
    {
        $this->subject = $this->helper_class_namespace::startWith($this->subject, $start_with);

        return $this;
    }

    public function titleCase(): StringSubjectDTO
    {
        $this->subject = $this->helper_class_namespace::titleCase($this->subject);

        return $this;
    }

    public function truncate(int $max_chars = Str::DEFAULT_TRUNCATE_SIZE): StringSubjectDTO
    {
        $this->subject = $this->helper_class_namespace::truncate($this->subject, $max_chars);

        return $this;
    }

    public function upper(): StringSubjectDTO
    {
        $this->subject = $this->helper_class_namespace::upper($this->subject);

        return $this;
    }
}
