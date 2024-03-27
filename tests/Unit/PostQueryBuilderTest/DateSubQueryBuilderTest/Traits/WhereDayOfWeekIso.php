<?php

namespace Wordless\Tests\Unit\PostQueryBuilderTest\DateSubQueryBuilderTest\Traits;

use Wordless\Tests\Unit\PostQueryBuilderTest\DateSubQueryBuilderTest\Traits\WhereDayOfWeekIso\WhereBetween;
use Wordless\Tests\Unit\PostQueryBuilderTest\DateSubQueryBuilderTest\Traits\WhereDayOfWeekIso\WhereEqual;
use Wordless\Tests\Unit\PostQueryBuilderTest\DateSubQueryBuilderTest\Traits\WhereDayOfWeekIso\WhereGreaterThan;
use Wordless\Tests\Unit\PostQueryBuilderTest\DateSubQueryBuilderTest\Traits\WhereDayOfWeekIso\WhereGreaterThanOrEqual;
use Wordless\Tests\Unit\PostQueryBuilderTest\DateSubQueryBuilderTest\Traits\WhereDayOfWeekIso\WhereIn;
use Wordless\Tests\Unit\PostQueryBuilderTest\DateSubQueryBuilderTest\Traits\WhereDayOfWeekIso\WhereLessThan;
use Wordless\Tests\Unit\PostQueryBuilderTest\DateSubQueryBuilderTest\Traits\WhereDayOfWeekIso\WhereLessThanOrEqual;
use Wordless\Tests\Unit\PostQueryBuilderTest\DateSubQueryBuilderTest\Traits\WhereDayOfWeekIso\WhereNotBetween;
use Wordless\Tests\Unit\PostQueryBuilderTest\DateSubQueryBuilderTest\Traits\WhereDayOfWeekIso\WhereNotEqual;
use Wordless\Tests\Unit\PostQueryBuilderTest\DateSubQueryBuilderTest\Traits\WhereDayOfWeekIso\WhereNotIn;

trait WhereDayOfWeekIso
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
