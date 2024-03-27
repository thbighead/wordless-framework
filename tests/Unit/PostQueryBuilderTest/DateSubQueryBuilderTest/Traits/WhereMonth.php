<?php

namespace Wordless\Tests\Unit\PostQueryBuilderTest\DateSubQueryBuilderTest\Traits;

use Wordless\Tests\Unit\PostQueryBuilderTest\DateSubQueryBuilderTest\Traits\WhereMonth\WhereBetween;
use Wordless\Tests\Unit\PostQueryBuilderTest\DateSubQueryBuilderTest\Traits\WhereMonth\WhereEqual;
use Wordless\Tests\Unit\PostQueryBuilderTest\DateSubQueryBuilderTest\Traits\WhereMonth\WhereGreaterThan;
use Wordless\Tests\Unit\PostQueryBuilderTest\DateSubQueryBuilderTest\Traits\WhereMonth\WhereGreaterThanOrEqual;
use Wordless\Tests\Unit\PostQueryBuilderTest\DateSubQueryBuilderTest\Traits\WhereMonth\WhereIn;
use Wordless\Tests\Unit\PostQueryBuilderTest\DateSubQueryBuilderTest\Traits\WhereMonth\WhereLessThan;
use Wordless\Tests\Unit\PostQueryBuilderTest\DateSubQueryBuilderTest\Traits\WhereMonth\WhereLessThanOrEqual;
use Wordless\Tests\Unit\PostQueryBuilderTest\DateSubQueryBuilderTest\Traits\WhereMonth\WhereNotBetween;
use Wordless\Tests\Unit\PostQueryBuilderTest\DateSubQueryBuilderTest\Traits\WhereMonth\WhereNotEqual;
use Wordless\Tests\Unit\PostQueryBuilderTest\DateSubQueryBuilderTest\Traits\WhereMonth\WhereNotIn;

trait WhereMonth
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
