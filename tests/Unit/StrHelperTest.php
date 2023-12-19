<?php

namespace Wordless\Tests\Unit;

use Wordless\Tests\Unit\StrHelperTest\Traits\CaseStyleTests;
use Wordless\Application\Helpers\Str;
use Wordless\Tests\Unit\StrHelperTest\Traits\UuidTests;
use Wordless\Tests\WordlessTestCase;

class StrHelperTest extends WordlessTestCase
{
    use CaseStyleTests;
    use UuidTests;

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
