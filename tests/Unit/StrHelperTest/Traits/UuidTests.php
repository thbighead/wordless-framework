<?php declare(strict_types=1);

namespace Wordless\Tests\Unit\StrHelperTest\Traits;

use PHPUnit\Framework\ExpectationFailedException;
use Wordless\Application\Helpers\Str;
use Wordless\Application\Helpers\Str\Enums\UuidVersion;
use Wordless\Tests\Unit\StrHelperTest;

/**
 * @mixin StrHelperTest
 */
trait UuidTests
{
    /**
     * @return void
     * @throws ExpectationFailedException
     */
    public function testVersion1(): void
    {
        $this->main(UuidVersion::one);
    }

    /**
     * @return void
     * @throws ExpectationFailedException
     */
    public function testVersion2(): void
    {
        $this->main(UuidVersion::two);
    }

    /**
     * @return void
     * @throws ExpectationFailedException
     */
    public function testVersion3(): void
    {
        $this->main(UuidVersion::three);
    }

    /**
     * @return void
     * @throws ExpectationFailedException
     */
    public function testVersion4(): void
    {
        $this->main(UuidVersion::four);
    }

    /**
     * @return void
     * @throws ExpectationFailedException
     */
    public function testVersion5(): void
    {
        $this->main(UuidVersion::five);
    }

    /**
     * @return void
     * @throws ExpectationFailedException
     */
    public function testVersion6(): void
    {
        $this->main(UuidVersion::six);
    }

    /**
     * @param UuidVersion $uuidVersion
     * @return void
     * @throws ExpectationFailedException
     */
    private function main(UuidVersion $uuidVersion): void
    {
        $this->assertTrue(Str::isUuid(Str::uuid($uuidVersion)));

        $this->assertFalse(Str::contains(Str::uuid($uuidVersion, false), ['-', '_']));
    }
}
