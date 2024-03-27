<?php declare(strict_types=1);

namespace Wordless\Application\Commands\Utility\DatabaseOverwrite\DTO;

use Faker\Factory;
use Faker\Generator;
use PasswordHash;
use Wordless\Application\Commands\Utility\DatabaseOverwrite\DTO\UserDTO\Exceptions\InvalidRawUserData;

final class UserDTO
{
    public const ATTRIBUTE_ID = 'ID';
    public const ATTRIBUTE_ACTIVATION_KEY = 'user_activation_key';
    public const RAW_ATTRIBUTES = [self::ATTRIBUTE_ID, self::ATTRIBUTE_ACTIVATION_KEY];
    public const USER_DEFAULT_OVERWRITE_PASSWORD_KEY = 'default_overwrite_password';

    private static Generator $faker;
    private static string $hashed_password;

    public readonly string $activation_key;
    public readonly string $email;
    public readonly int $id;
    public readonly string $password;

    /**
     * @param object $rawUser
     * @throws InvalidRawUserData
     */
    public function __construct(private readonly object $rawUser)
    {
        $this->validateRawUser();

        if (!isset(self::$faker)) {
            self::$faker = Factory::create();
        }

        if (!isset(self::$hashed_password)) {
            self::$hashed_password = wp_hash_password(
                $this->configurations[self::USER_DEFAULT_OVERWRITE_PASSWORD_KEY] ?? 'password'
            );
        }

        $this->id = $this->getRawId();
        $this->email = $this->generateSafeEmail();
        $this->activation_key = $this->hasActivationKey() ? $this->generateHashedActivationKey() : '';
        $this->password = self::$hashed_password;
    }

    public function getRawId(): int
    {
        return (int)$this->rawUser->{self::ATTRIBUTE_ID};
    }

    public function getRawUserActivationKey(): string
    {
        return (string)$this->rawUser->{self::ATTRIBUTE_ACTIVATION_KEY};
    }

    public function hasActivationKey(): bool
    {
        return $this->getRawUserActivationKey() !== '';
    }

    private function generateHashedActivationKey(): string
    {
        $wpBaseHash = new PasswordHash(8, true);

        return time() . ":{$wpBaseHash->HashPassword(wp_generate_password(20, false))}";
    }

    private function generateSafeEmail(): string
    {
        return self::$faker->unique()->safeEmail;
    }

    /**
     * @return void
     * @throws InvalidRawUserData
     */
    private function validateRawUser(): void
    {
        foreach (self::RAW_ATTRIBUTES as $raw_attribute) {
            if (!isset($this->rawUser->$raw_attribute)) {
                throw new InvalidRawUserData((array)$this->rawUser);
            }
        }
    }
}
