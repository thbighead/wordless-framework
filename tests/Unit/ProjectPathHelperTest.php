<?php declare(strict_types=1);

namespace Wordless\Tests\Unit;

use PHPUnit\Framework\ExpectationFailedException;
use Wordless\Application\Helpers\ProjectPath;
use Wordless\Application\Helpers\ProjectPath\Exceptions\PathNotFoundException;
use Wordless\Application\Helpers\Str;
use Wordless\Tests\WordlessTestCase;

class ProjectPathHelperTest extends WordlessTestCase
{
    /**
     * @return void
     * @throws PathNotFoundException
     * @throws ExpectationFailedException
     */
    public function testAppPath()
    {
        $this->assertEquals(
            realpath(__DIR__ . '/../..') . DIRECTORY_SEPARATOR . 'app',
            ProjectPath::app()
        );
    }

    /**
     * @return void
     * @throws PathNotFoundException
     */
    public function testInvalidPath()
    {
        $this->expectException(PathNotFoundException::class);

        ProjectPath::root(Str::uuid());
    }
}
