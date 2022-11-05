<?php

namespace Wordless\Tests\Unit\StrHelperTest;

use Wordless\Tests\Unit\StrHelperTest\CaseStyleTests\FromCamelToAnother;
use Wordless\Tests\Unit\StrHelperTest\CaseStyleTests\FromKebabToAnother;
use Wordless\Tests\Unit\StrHelperTest\CaseStyleTests\FromPascalToAnother;
use Wordless\Tests\Unit\StrHelperTest\CaseStyleTests\FromRawToAnother;
use Wordless\Tests\Unit\StrHelperTest\CaseStyleTests\FromSnakeToAnother;
use Wordless\Tests\Unit\StrHelperTest\CaseStyleTests\FromTitleToAnother;

trait CaseStyleTests
{
    use FromCamelToAnother,
        FromKebabToAnother,
        FromPascalToAnother,
        FromTitleToAnother,
        FromRawToAnother,
        FromSnakeToAnother;
}
