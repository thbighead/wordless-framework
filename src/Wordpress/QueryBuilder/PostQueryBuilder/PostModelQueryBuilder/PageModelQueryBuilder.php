<?php declare(strict_types=1);

namespace Wordless\Wordpress\QueryBuilder\PostQueryBuilder\PostModelQueryBuilder;

use Wordless\Infrastructure\Wordpress\QueryBuilder\Exceptions\EmptyQueryBuilderArguments;
use Wordless\Wordpress\Models\Page;
use Wordless\Wordpress\Models\Post\Exceptions\InitializingModelWithWrongPostType;
use Wordless\Wordpress\Models\Post\Traits\Crud\Traits\Delete\Exceptions\WpDeletePostFailed;
use Wordless\Wordpress\Models\PostType\Exceptions\PostTypeNotRegistered;
use Wordless\Wordpress\QueryBuilder\PostQueryBuilder\PostModelQueryBuilder;
use Wordless\Wordpress\QueryBuilder\PostQueryBuilder\PostModelQueryBuilder\Exceptions\InvalidMethodException;
use Wordless\Wordpress\QueryBuilder\PostQueryBuilder\PostModelQueryBuilder\Exceptions\InvalidModelClass;
use Wordless\Wordpress\Models\Page\Traits\Crud\Traits\CreateAndUpdate\Builder;
use Wordless\Wordpress\QueryBuilder\PostQueryBuilder\PostModelQueryBuilder\PageModelQueryBuilder\MultipleUpdateBuilder;

class PageModelQueryBuilder extends PostModelQueryBuilder
{
    /**
     * @param string $name
     * @param array $arguments
     * @return $this|array|bool|int|string|Page|null
     * @throws InitializingModelWithWrongPostType
     * @throws InvalidMethodException
     * @throws PostTypeNotRegistered
     */
    public function __call(string $name, array $arguments): array|bool|int|string|static|Page|null
    {
        return parent::__call($name, $arguments);
    }

    /**
     * @param class-string|Page $model_class_namespace
     * @throws InvalidModelClass
     */
    public function __construct(string $model_class_namespace)
    {
        if (!is_a($model_class_namespace, $correct_class_namespace = Page::class, true)) {
            throw new InvalidModelClass($model_class_namespace, $correct_class_namespace);
        }

        parent::__construct($model_class_namespace);
    }

    /**
     * @return MultipleUpdateBuilder
     * @throws EmptyQueryBuilderArguments
     */
    public function buildEdit(): Builder
    {
        return new MultipleUpdateBuilder($this, $this->model_class_namespace::postType());
    }

    /**
     * @return Page[]
     * @throws EmptyQueryBuilderArguments
     * @throws WpDeletePostFailed
     */
    public function delete(): array
    {
        return parent::delete();
    }

    /**
     * @return Page[]
     * @throws EmptyQueryBuilderArguments
     * @throws WpDeletePostFailed
     */
    public function trash(): array
    {
        return parent::trash();
    }
}
