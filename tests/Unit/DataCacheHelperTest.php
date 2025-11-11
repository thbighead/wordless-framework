<?php declare(strict_types=1);

namespace Wordless\Tests\Unit;

use PHPUnit\Framework\Attributes\Depends;
use PHPUnit\Framework\ExpectationFailedException;
use Random\RandomException;
use ReflectionException;
use ReflectionMethod;
use Wordless\Application\Helpers\DataCache;
use Wordless\Application\Helpers\DataCache\Contracts\Subjectable\DTO\DataCacheSubjectDTO;
use Wordless\Application\Helpers\DataCache\Exceptions\FailedToSetTransient;
use Wordless\Application\Helpers\DataCache\Exceptions\InvalidTransientExpirationValue;
use Wordless\Application\Helpers\DataCache\Exceptions\TransientKeyIsTooLong;
use Wordless\Application\Helpers\DataCache\Exceptions\TransientKeyNotFound;
use Wordless\Application\Helpers\Str;
use Wordless\Application\Libraries\Carbon\Carbon;
use Wordless\Tests\WordlessTestCase;
use Wordless\Tests\WordlessTestCase\Traits\SubjectDtoHelperTests;

class DataCacheHelperTest extends WordlessTestCase
{
    use SubjectDtoHelperTests;

    private const KEY = 'test';
    private const EXPIRED_KEY = 'expired';
    private const EXPIRATION_SECONDS = 2;

    /**
     * @return void
     * @throws FailedToSetTransient
     * @throws InvalidTransientExpirationValue
     * @throws TransientKeyIsTooLong
     */
    public static function setUpBeforeClass(): void
    {
        parent::setUpBeforeClass();

        try {
            DataCache::delete(self::KEY);
        } catch (TransientKeyNotFound) {
        }
        try {
            DataCache::delete(self::EXPIRED_KEY);
        } catch (TransientKeyNotFound) {
        }

        DataCache::set(self::KEY, StrHelperTest::BASE_STRING);
        DataCache::set(self::EXPIRED_KEY, '', Carbon::now()->addSeconds(self::EXPIRATION_SECONDS));
    }

    /**
     * @return void
     * @throws TransientKeyNotFound
     * @throws ExpectationFailedException
     */
    #[Depends('testGet')]
    public function testDelete(): void
    {
        $default = 't';

        DataCache::delete(self::KEY);

        $this->assertNull(DataCache::get(self::KEY));
        $this->assertEquals($default, DataCache::get(self::KEY, $default));

        $this->expectException(TransientKeyNotFound::class);
        DataCache::delete(self::KEY);
    }

    /**
     * @return void
     * @throws ExpectationFailedException
     */
    public function testGet(): void
    {
        $default = 't';

        $this->assertEquals('', DataCache::get(self::EXPIRED_KEY));
        $this->assertEquals(StrHelperTest::BASE_STRING, DataCache::get(self::KEY));
        sleep(self::EXPIRATION_SECONDS + 1);
        $this->assertNull(DataCache::get(self::EXPIRED_KEY));
        $this->assertEquals($default, DataCache::get(self::EXPIRED_KEY, $default));
    }

    /**
     * @return void
     * @throws FailedToSetTransient
     * @throws InvalidTransientExpirationValue
     * @throws TransientKeyIsTooLong
     */
    public function testSetInvalidTransientExpirationValue(): void
    {
        $this->expectException(InvalidTransientExpirationValue::class);
        DataCache::set(
            'new',
            StrHelperTest::BASE_STRING,
            'invalid'
        );
    }

    /**
     * @return void
     * @throws FailedToSetTransient
     * @throws InvalidTransientExpirationValue
     * @throws RandomException
     * @throws TransientKeyIsTooLong
     */
    public function testSetTransientKeyIsTooLong(): void
    {
        $this->expectException(TransientKeyIsTooLong::class);
        DataCache::set(
            Str::random(175),
            StrHelperTest::BASE_STRING
        );
    }

    /**
     * @return void
     * @throws ExpectationFailedException
     * @throws ReflectionException
     */
    public function testSubjectDto(): void
    {
        $this->assertSubjectDtoMethods(['of']);
    }

    /**
     * @param ReflectionMethod $subjectMethod
     * @param ReflectionMethod $helperMethod
     * @return void
     * @throws ExpectationFailedException
     */
    private function assertSimilarMethodsReturnTypes(
        ReflectionMethod $subjectMethod,
        ReflectionMethod $helperMethod
    ): void
    {
        $subject_method_return_type = Str::replace(
            (string)$subjectMethod->getReturnType(),
            ['static', 'self'],
            DataCacheSubjectDTO::class
        );

        match ($subjectMethod->name) {
            default => $this->assertEquals(
                (string)$helperMethod->getReturnType(),
                $subject_method_return_type,
                "Failed asserting that helper and subject method $helperMethod->name return types are equal."
            )
        };
    }

    private function subject(): string
    {
        return self::KEY;
    }
}
