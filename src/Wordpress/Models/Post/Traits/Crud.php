<?php declare(strict_types=1);

namespace Wordless\Wordpress\Models\Post\Traits;

use Wordless\Application\Helpers\Str;
use Wordless\Application\Helpers\Str\Traits\Internal\Exceptions\FailedToCreateInflector;
use Wordless\Infrastructure\Wordpress\QueryBuilder\Exceptions\EmptyQueryBuilderArguments;
use Wordless\Wordpress\Models\Post\Traits\Crud\Exceptions\FindOrCreateFailed;
use Wordless\Wordpress\Models\Post\Traits\Crud\Traits\CreateAndUpdate;
use Wordless\Wordpress\Models\Post\Traits\Crud\Traits\Delete;
use Wordless\Wordpress\Models\Post\Traits\Crud\Traits\Read;
use Wordless\Wordpress\QueryBuilder\PostQueryBuilder\PostModelQueryBuilder;

trait Crud
{
    use CreateAndUpdate;
    use Delete;
    use Read;

    /**
     * @param string $slug
     * @param string|null $title
     * @return static
     * @throws FindOrCreateFailed
     */
    public static function findOrCreate(string $slug, ?string $title = null): static
    {
        try {
            /** @noinspection PhpPossiblePolymorphicInvocationInspection */
            return static::findBySlug($slug)
                ?? new static(static::buildNew($title ?? Str::titleCase($slug))->slug($slug)->create());
        } catch (FailedToCreateInflector $exception) {
            throw new FindOrCreateFailed(
                "Failed to create when trying to convert slug '$slug' to title case.",
                $exception
            );
        } catch (EmptyQueryBuilderArguments $exception) {
            throw new FindOrCreateFailed("Failed to find slug '$slug'.", $exception);
        }
    }

    public static function query(): PostModelQueryBuilder
    {
        return new PostModelQueryBuilder(static::class);
    }
}
