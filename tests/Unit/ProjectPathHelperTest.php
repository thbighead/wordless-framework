<?php

namespace Wordless\Tests\Unit;

use Wordless\Exceptions\InvalidUuidVersion;
use Wordless\Exceptions\PathNotFoundException;
use Wordless\Helpers\ProjectPath;
use Wordless\Helpers\Str;
use Wordless\Tests\WordlessTestCase;

class ProjectPathHelperTest extends WordlessTestCase
{
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
