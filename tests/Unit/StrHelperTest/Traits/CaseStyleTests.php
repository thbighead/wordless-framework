<?php

namespace Wordless\Tests\Unit\StrHelperTest\Traits;

use Wordless\Tests\Unit\StrHelperTest\Traits\CaseStyleTests\Traits\FromCamelToAnother;
use Wordless\Tests\Unit\StrHelperTest\Traits\CaseStyleTests\Traits\FromKebabToAnother;
use Wordless\Tests\Unit\StrHelperTest\Traits\CaseStyleTests\Traits\FromPascalToAnother;
use Wordless\Tests\Unit\StrHelperTest\Traits\CaseStyleTests\Traits\FromRawToAnother;
use Wordless\Tests\Unit\StrHelperTest\Traits\CaseStyleTests\Traits\FromSnakeToAnother;
use Wordless\Tests\Unit\StrHelperTest\Traits\CaseStyleTests\Traits\FromTitleToAnother;

trait CaseStyleTests
{
    use FromCamelToAnother;
    use FromKebabToAnother;
    use FromPascalToAnother;
    use FromTitleToAnother;
    use FromRawToAnother;
    use FromSnakeToAnother;

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
}
