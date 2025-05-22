<?php declare(strict_types=1);

namespace Wordless\Wordpress\Models\Post\Traits;

use InvalidArgumentException;
use Wordless\Application\Helpers\Str;
use Wordless\Infrastructure\Wordpress\QueryBuilder\Exceptions\EmptyQueryBuilderArguments;
use Wordless\Wordpress\Models\Post\Traits\Crud\Traits\CreateAndUpdate;
use Wordless\Wordpress\Models\Post\Traits\Crud\Traits\Delete;
use Wordless\Wordpress\Models\Post\Traits\Crud\Traits\Read;
use Wordless\Wordpress\QueryBuilder\PostQueryBuilder;

trait Crud
{
    use CreateAndUpdate;
    use Delete;
    use Read;

    /**
     * @param string $slug
     * @param string|null $title
     * @return static
     * @throws InvalidArgumentException
     * @throws EmptyQueryBuilderArguments
     */
    public static function findOrCreate(string $slug, ?string $title = null): static
    {
        /** @noinspection PhpPossiblePolymorphicInvocationInspection */
        return static::findBySlug($slug)
            ?? new static(static::buildNew($title ?? Str::titleCase($slug))->slug($slug)->create());
    }

    private static function query(): PostQueryBuilder
    {
        return new PostQueryBuilder(static::postType());
    }
}
