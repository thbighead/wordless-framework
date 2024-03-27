<?php

namespace Wordless\Tests\Unit\StrHelperTest\Traits;

use Wordless\Application\Helpers\Str;
use Wordless\Application\Helpers\Str\Enums\UuidVersion;

trait UuidTests
{
    public function testDefault(): void
    {
        $this->assertTrue(Str::isUuid(Str::uuid()));
    }

    public function testVersion1(): void
    {
        $this->assertTrue(Str::isUuid(Str::uuid(UuidVersion::one)));
    }

    public function testVersion2(): void
    {
        $this->assertTrue(Str::isUuid(Str::uuid(UuidVersion::two)));
    }

    public function testVersion3(): void
    {
        $this->assertTrue(Str::isUuid(Str::uuid(UuidVersion::three)));
    }

    public function testVersion4(): void
    {
        /** @noinspection PhpRedundantOptionalArgumentInspection */
        $this->assertTrue(Str::isUuid(Str::uuid(UuidVersion::four)));
    }

    public function testVersion5(): void
    {
        $this->assertTrue(Str::isUuid(Str::uuid(UuidVersion::five)));
    }

    public function testVersion6(): void
    {
        $this->assertTrue(Str::isUuid(Str::uuid(UuidVersion::six)));
    }
}
