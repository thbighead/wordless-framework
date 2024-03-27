<?php

namespace Wordless\Tests\Unit\PostQueryBuilderTest\DateSubQueryBuilderTest\Traits;

use Wordless\Tests\Unit\PostQueryBuilderTest\DateSubQueryBuilderTest\Traits\WhereSecond\WhereBetween;
use Wordless\Tests\Unit\PostQueryBuilderTest\DateSubQueryBuilderTest\Traits\WhereSecond\WhereEqual;
use Wordless\Tests\Unit\PostQueryBuilderTest\DateSubQueryBuilderTest\Traits\WhereSecond\WhereGreaterThan;
use Wordless\Tests\Unit\PostQueryBuilderTest\DateSubQueryBuilderTest\Traits\WhereSecond\WhereGreaterThanOrEqual;
use Wordless\Tests\Unit\PostQueryBuilderTest\DateSubQueryBuilderTest\Traits\WhereSecond\WhereIn;
use Wordless\Tests\Unit\PostQueryBuilderTest\DateSubQueryBuilderTest\Traits\WhereSecond\WhereLessThan;
use Wordless\Tests\Unit\PostQueryBuilderTest\DateSubQueryBuilderTest\Traits\WhereSecond\WhereLessThanOrEqual;
use Wordless\Tests\Unit\PostQueryBuilderTest\DateSubQueryBuilderTest\Traits\WhereSecond\WhereNotBetween;
use Wordless\Tests\Unit\PostQueryBuilderTest\DateSubQueryBuilderTest\Traits\WhereSecond\WhereNotEqual;
use Wordless\Tests\Unit\PostQueryBuilderTest\DateSubQueryBuilderTest\Traits\WhereSecond\WhereNotIn;

trait WhereSecond
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
