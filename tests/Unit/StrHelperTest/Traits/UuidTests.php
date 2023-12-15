<?php

namespace StrHelperTest\Traits;

use Wordless\Application\Helpers\Str;
use Wordless\Exceptions\InvalidUuidVersion;

trait UuidTests
{
    /**
     * @return void
     * @throws InvalidUuidVersion
     */
    public function testInvalidVersion()
    {
        $this->expectException(InvalidUuidVersion::class);

        Str::uuid(-1);
    }

    /**
     * @return void
     * @throws InvalidUuidVersion
     */
    public function testDefault()
    {
        $this->assertTrue(Str::isUuid(Str::uuid()));
    }

    /**
     * @return void
     * @throws InvalidUuidVersion
     */
    public function testVersion1()
    {
        $this->assertTrue(Str::isUuid(Str::uuid(1)));
    }

    /**
     * @return void
     * @throws InvalidUuidVersion
     */
    public function testVersion2()
    {
        $this->assertTrue(Str::isUuid(Str::uuid(2)));
    }

    /**
     * @return void
     * @throws InvalidUuidVersion
     */
    public function testVersion3()
    {
        $this->assertTrue(Str::isUuid(Str::uuid(3)));
    }

    /**
     * @return void
     * @throws InvalidUuidVersion
     */
    public function testVersion4()
    {
        /** @noinspection PhpRedundantOptionalArgumentInspection */
        $this->assertTrue(Str::isUuid(Str::uuid(4)));
    }

    /**
     * @return void
     * @throws InvalidUuidVersion
     */
    public function testVersion5()
    {
        $this->assertTrue(Str::isUuid(Str::uuid(5)));
    }

    /**
     * @return void
     * @throws InvalidUuidVersion
     */
    public function testVersion6()
    {
        $this->assertTrue(Str::isUuid(Str::uuid(6)));
    }
}
