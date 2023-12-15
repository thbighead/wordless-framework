<?php

namespace Wordless\Tests\Unit;

use StrHelperTest\Traits\CaseStyleTests;
use StrHelperTest\Traits\UuidTests;
use Wordless\Application\Helpers\Str;
use Wordless\Tests\WordlessTestCase;

class StrHelperTest extends WordlessTestCase
{
    use CaseStyleTests, UuidTests;

    private const CLEAN_RAW_CASE_EXAMPLE = 'thanks for reading';
    private const CLEAN_TITLE_CASE_EXAMPLE = 'Thanks For Reading';
    private const CLEAN_CAMEL_CASE_EXAMPLE = 'thanksForReading';
    private const CLEAN_PASCAL_CASE_EXAMPLE = 'ThanksForReading';
    private const CLEAN_SNAKE_CASE_EXAMPLE = 'thanks_for_reading';
    private const CLEAN_KEBAB_CASE_EXAMPLE = 'thanks-for-reading';

    private const NUMERICAL_RAW_CASE_EXAMPLE = 'thanks 4 reading';
    private const NUMERICAL_TITLE_CASE_EXAMPLE = 'Thanks 4 Reading';
    private const NUMERICAL_CAMEL_CASE_EXAMPLE = 'thanks4Reading';
    private const NUMERICAL_PASCAL_CASE_EXAMPLE = 'Thanks4Reading';
    private const NUMERICAL_SNAKE_CASE_EXAMPLE = 'thanks_4_reading';
    private const NUMERICAL_KEBAB_CASE_EXAMPLE = 'thanks-4-reading';

    private const FINISHING_WORD = ' example';
    private const EXCLAMATION_MARK = '!';
    private const INTERROGATION_MARK = '?';
    private const SLASH = '/';

    public function testFinishWith()
    {
        $pure_string = 'a pure string';
        $string_finished_with_slash = $pure_string . self::SLASH;
        $string_finished_with_exclamation_mark = $pure_string . self::EXCLAMATION_MARK;
        $string_finished_with_interrogation_mark = $pure_string . self::INTERROGATION_MARK;
        $string_finished_with_example = $pure_string . self::FINISHING_WORD;

        $this->assertEquals(
            $string_finished_with_slash,
            Str::finishWith($pure_string, self::SLASH)
        );
        $this->assertEquals(
            $string_finished_with_slash,
            Str::finishWith($string_finished_with_slash, self::SLASH)
        );

        $this->assertEquals(
            $string_finished_with_exclamation_mark,
            Str::finishWith($pure_string, self::EXCLAMATION_MARK)
        );
        $this->assertEquals(
            $string_finished_with_exclamation_mark,
            Str::finishWith($string_finished_with_exclamation_mark, self::EXCLAMATION_MARK)
        );

        $this->assertEquals(
            $string_finished_with_interrogation_mark,
            Str::finishWith($pure_string, self::INTERROGATION_MARK)
        );
        $this->assertEquals(
            $string_finished_with_interrogation_mark,
            Str::finishWith($string_finished_with_interrogation_mark, self::INTERROGATION_MARK)
        );

        $this->assertEquals(
            $string_finished_with_example,
            Str::finishWith($pure_string, self::FINISHING_WORD)
        );
        $this->assertEquals(
            $string_finished_with_example,
            Str::finishWith($string_finished_with_example, self::FINISHING_WORD)
        );
    }
}
