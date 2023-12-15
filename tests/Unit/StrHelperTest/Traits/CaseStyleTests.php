<?php

namespace StrHelperTest\Traits;

use StrHelperTest\Traits\CaseStyleTests\Traits\FromCamelToAnother;
use StrHelperTest\Traits\CaseStyleTests\Traits\FromKebabToAnother;
use StrHelperTest\Traits\CaseStyleTests\Traits\FromPascalToAnother;
use StrHelperTest\Traits\CaseStyleTests\Traits\FromRawToAnother;
use StrHelperTest\Traits\CaseStyleTests\Traits\FromSnakeToAnother;
use StrHelperTest\Traits\CaseStyleTests\Traits\FromTitleToAnother;

trait CaseStyleTests
{
    use FromCamelToAnother,
        FromKebabToAnother,
        FromPascalToAnother,
        FromTitleToAnother,
        FromRawToAnother,
        FromSnakeToAnother;
}
