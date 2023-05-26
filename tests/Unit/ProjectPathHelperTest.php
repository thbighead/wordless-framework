<?php

namespace Wordless\Tests\Unit;

use Wordless\Application\Helpers\ProjectPath;
use Wordless\Application\Helpers\ProjectPath\Exceptions\PathNotFoundException;
use Wordless\Application\Helpers\Str;
use Wordless\Exceptions\InvalidUuidVersion;
use Wordless\Tests\Contracts\NeedsTestEnvironment;
use Wordless\Tests\WordlessTestCase;

class ProjectPathHelperTest extends WordlessTestCase
{
    use NeedsTestEnvironment;

    /**
     * @return void
     * @throws PathNotFoundException
     */
    public function testAppPath()
    {
        $this->assertEquals(realpath(__DIR__ . '/../../test-environment/app'), ProjectPath::app());
    }

    /**
     * @return void
     * @throws PathNotFoundException
     * @throws InvalidUuidVersion
     */
    public function testInvalidPath()
    {
        $this->expectException(PathNotFoundException::class);

        ProjectPath::root(Str::uuid());
    }
}
