<?php declare(strict_types=1);

namespace Wordless\Infrastructure\Wordpress\CustomPostStatus\Traits;

use Wordless\Application\Guessers\CustomPostStatusNameGuesser;
use Wordless\Infrastructure\Wordpress\CustomPostStatus\Traits\Register\Traits\Validation;
use Wordless\Infrastructure\Wordpress\CustomPostStatus\Traits\Register\Traits\Validation\Exceptions\ReservedCustomPostStatusKey;

trait Register
{
    use Validation;

    private static array $status_names = [];

    final public static function getName(): string
    {
        return self::$status_names[static::class] ??
            self::$status_names[static::class] = static::NAME ??
                (new CustomPostStatusNameGuesser(static::class))->getValue();
    }

    /**
     * @return void
     * @throws ReservedCustomPostStatusKey
     */
    final public static function register(): void
    {
        self::validateName();

        register_post_status(static::getName(), static::mountArguments());
    }

    protected static function label(): ?string
    {
        return null;
    }

    protected static function isDateFloating(): bool
    {
        return false;
    }

    protected static function isExcludedFromSearch(): bool
    {
        return static::isInternal();
    }

    protected static function isPrivate(): bool
    {
        return false;
    }

    protected static function isProtected(): bool
    {
        return false;
    }

    protected static function isPublic(): bool
    {
        return false;
    }

    protected static function isPubliclyQueryable(): bool
    {
        return static::isPublic();
    }

    protected static function isInternal(): bool
    {
        return false;
    }

    protected static function shouldIncludePostsInEditList(): bool
    {
        return !static::isInternal();
    }

    protected static function shouldAppearInAdminStatusList(): bool
    {
        return !static::isInternal();
    }

    private static function mountArguments(): array
    {
        $arguments = [
            'exclude_from_search' => static::isExcludedFromSearch(),
            'public' => static::isPublic(),
            'internal' => static::isInternal(),
            'protected' => static::isProtected(),
            'private' => static::isPrivate(),
            'publicly_queryable' => static::isPubliclyQueryable(),
            'show_in_admin_all_list' => static::shouldIncludePostsInEditList(),
            'show_in_admin_status_list' => static::shouldAppearInAdminStatusList(),
            'date_floating' => static::isDateFloating(),
        ];

        if (!is_null($label = static::label())) {
            $arguments['label'] = $label;
        }

        return $arguments;
    }
}
