<?php

namespace Wordless\Tests\Unit\PostQueryBuilderTest\DateSubQueryBuilderTest\Traits;

use Wordless\Tests\Unit\PostQueryBuilderTest\DateSubQueryBuilderTest\Traits\WhereMinute\WhereBetween;
use Wordless\Tests\Unit\PostQueryBuilderTest\DateSubQueryBuilderTest\Traits\WhereMinute\WhereEqual;
use Wordless\Tests\Unit\PostQueryBuilderTest\DateSubQueryBuilderTest\Traits\WhereMinute\WhereGreaterThan;
use Wordless\Tests\Unit\PostQueryBuilderTest\DateSubQueryBuilderTest\Traits\WhereMinute\WhereGreaterThanOrEqual;
use Wordless\Tests\Unit\PostQueryBuilderTest\DateSubQueryBuilderTest\Traits\WhereMinute\WhereIn;
use Wordless\Tests\Unit\PostQueryBuilderTest\DateSubQueryBuilderTest\Traits\WhereMinute\WhereLessThan;
use Wordless\Tests\Unit\PostQueryBuilderTest\DateSubQueryBuilderTest\Traits\WhereMinute\WhereLessThanOrEqual;
use Wordless\Tests\Unit\PostQueryBuilderTest\DateSubQueryBuilderTest\Traits\WhereMinute\WhereNotBetween;
use Wordless\Tests\Unit\PostQueryBuilderTest\DateSubQueryBuilderTest\Traits\WhereMinute\WhereNotEqual;
use Wordless\Tests\Unit\PostQueryBuilderTest\DateSubQueryBuilderTest\Traits\WhereMinute\WhereNotIn;

trait WhereMinute
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
