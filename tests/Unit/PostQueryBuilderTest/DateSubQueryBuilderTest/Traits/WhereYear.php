<?php

namespace Wordless\Tests\Unit\PostQueryBuilderTest\DateSubQueryBuilderTest\Traits;

use Wordless\Tests\Unit\PostQueryBuilderTest\DateSubQueryBuilderTest\Traits\WhereYear\WhereBetween;
use Wordless\Tests\Unit\PostQueryBuilderTest\DateSubQueryBuilderTest\Traits\WhereYear\WhereEqual;
use Wordless\Tests\Unit\PostQueryBuilderTest\DateSubQueryBuilderTest\Traits\WhereYear\WhereGreaterThan;
use Wordless\Tests\Unit\PostQueryBuilderTest\DateSubQueryBuilderTest\Traits\WhereYear\WhereGreaterThanOrEqual;
use Wordless\Tests\Unit\PostQueryBuilderTest\DateSubQueryBuilderTest\Traits\WhereYear\WhereIn;
use Wordless\Tests\Unit\PostQueryBuilderTest\DateSubQueryBuilderTest\Traits\WhereYear\WhereLessThan;
use Wordless\Tests\Unit\PostQueryBuilderTest\DateSubQueryBuilderTest\Traits\WhereYear\WhereLessThanOrEqual;
use Wordless\Tests\Unit\PostQueryBuilderTest\DateSubQueryBuilderTest\Traits\WhereYear\WhereNotBetween;
use Wordless\Tests\Unit\PostQueryBuilderTest\DateSubQueryBuilderTest\Traits\WhereYear\WhereNotEqual;
use Wordless\Tests\Unit\PostQueryBuilderTest\DateSubQueryBuilderTest\Traits\WhereYear\WhereNotIn;

trait WhereYear
{
    use WhereBetween;
    use WhereEqual;
    use WhereGreaterThan;
    use WhereGreaterThanOrEqual;
    use WhereIn;
    use WhereLessThan;
    use WhereLessThanOrEqual;
    use WhereNotBetween;
    use WhereNotEqual;
    use WhereNotIn;
}
