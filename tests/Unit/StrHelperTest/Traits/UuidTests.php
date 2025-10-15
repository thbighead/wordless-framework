<?php

namespace Wordless\Tests\Unit\StrHelperTest\Traits;

use PHPUnit\Framework\ExpectationFailedException;
use Wordless\Application\Helpers\Str;
use Wordless\Application\Helpers\Str\Enums\UuidVersion;

trait UuidTests
{
    /**
     * @return void
     * @throws ExpectationFailedException
     */
    public function testVersion1(): void
    {
        $undashed_uuid = Str::uuid(UuidVersion::one, false);

        $this->assertTrue(Str::isUuid(Str::uuid(UuidVersion::one)));
        $this->assertTrue(Str::isUuid($undashed_uuid));

        $this->assertFalse(Str::contains($undashed_uuid, ['-', '_']));
    }

    /**
     * @return void
     * @throws ExpectationFailedException
     */
    public function testVersion2(): void
    {
        $undashed_uuid = Str::uuid(UuidVersion::two, false);

        $this->assertTrue(Str::isUuid(Str::uuid(UuidVersion::two)));
        $this->assertTrue(Str::isUuid($undashed_uuid));

        $this->assertFalse(Str::contains($undashed_uuid, ['-', '_']));
    }

    /**
     * @return void
     * @throws ExpectationFailedException
     */
    public function testVersion3(): void
    {
        $undashed_uuid = Str::uuid(UuidVersion::three, false);

        $this->assertTrue(Str::isUuid(Str::uuid(UuidVersion::three)));
        $this->assertTrue(Str::isUuid($undashed_uuid));

        $this->assertFalse(Str::contains($undashed_uuid, ['-', '_']));
    }

    /**
     * @return void
     * @throws ExpectationFailedException
     */
    public function testVersion4(): void
    {
        $undashed_uuid = Str::uuid(UuidVersion::four, false);

        $this->assertTrue(Str::isUuid(Str::uuid()));
        $this->assertTrue(Str::isUuid($undashed_uuid));

        $this->assertFalse(Str::contains($undashed_uuid, ['-', '_']));
    }

    /**
     * @return void
     * @throws ExpectationFailedException
     */
    public function testVersion5(): void
    {
        $undashed_uuid = Str::uuid(UuidVersion::five, false);

        $this->assertTrue(Str::isUuid(Str::uuid(UuidVersion::five)));
        $this->assertTrue(Str::isUuid($undashed_uuid));

        $this->assertFalse(Str::contains($undashed_uuid, ['-', '_']));
    }

    /**
     * @return void
     * @throws ExpectationFailedException
     */
    public function testVersion6(): void
    {
        $undashed_uuid = Str::uuid(UuidVersion::six, false);

        $this->assertTrue(Str::isUuid(Str::uuid(UuidVersion::six)));
        $this->assertTrue(Str::isUuid($undashed_uuid));

        $this->assertFalse(Str::contains($undashed_uuid, ['-', '_']));
    }
}
