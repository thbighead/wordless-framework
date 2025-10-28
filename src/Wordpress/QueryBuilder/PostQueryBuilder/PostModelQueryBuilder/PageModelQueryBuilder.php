<?php declare(strict_types=1);

namespace Wordless\Wordpress\QueryBuilder\PostQueryBuilder\PostModelQueryBuilder;

use Wordless\Application\Helpers\Database;
use Wordless\Application\Helpers\Database\Exceptions\QueryError;
use Wordless\Infrastructure\Wordpress\QueryBuilder\Exceptions\EmptyQueryBuilderArguments;
use Wordless\Wordpress\Models\Page;
use Wordless\Wordpress\Models\Page\Traits\Crud\Traits\CreateAndUpdate\Builder\UpdateBuilder;
use Wordless\Wordpress\Models\Post\Exceptions\InitializingModelWithWrongPostType;
use Wordless\Wordpress\Models\Post\Traits\Crud\Traits\Delete\Exceptions\WpDeletePostFailed;
use Wordless\Wordpress\Models\PostType\Exceptions\PostTypeNotRegistered;
use Wordless\Wordpress\QueryBuilder\Exceptions\InvalidMethodException;
use Wordless\Wordpress\QueryBuilder\PostQueryBuilder\PostModelQueryBuilder;
use Wordless\Wordpress\QueryBuilder\PostQueryBuilder\PostModelQueryBuilder\Exceptions\InvalidModelClass;
use Wordless\Wordpress\QueryBuilder\PostQueryBuilder\PostModelQueryBuilder\PageModelQueryBuilder\Exceptions\FailedToUpdatePages;
use Wordless\Wordpress\QueryBuilder\PostQueryBuilder\PostModelQueryBuilder\PageModelQueryBuilder\Exceptions\UpdateAnonymousFunctionDidNotReturnUpdateBuilderObject;

class PageModelQueryBuilder extends PostModelQueryBuilder
{
    /**
     * @param string $name
     * @param array $arguments
     * @return $this|array|bool|int|Page|null
     * @throws InitializingModelWithWrongPostType
     * @throws InvalidMethodException
     * @throws PostTypeNotRegistered
     */
    public function __call(string $name, array $arguments): array|bool|int|static|Page|null
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

    /**
     * @param callable $item_changes
     * @param bool $firing_after_events
     * @return Page[]
     * @throws FailedToUpdatePages
     * @throws UpdateAnonymousFunctionDidNotReturnUpdateBuilderObject
     * @noinspection PhpExceptionImmediatelyRethrownInspection
     */
    public function update(callable $item_changes, bool $firing_after_events = true): array
    {
        try {
            /** @var Page[] $posts */
            $posts = $this->get();

            Database::smartTransaction(function () use ($posts, $item_changes, $firing_after_events) {
                foreach ($posts as $post) {
                    $postUpdateBuilder = $item_changes($post);

                    if ($postUpdateBuilder instanceof UpdateBuilder) {
                        $postUpdateBuilder->update($firing_after_events);
                        continue;
                    }

                    throw new UpdateAnonymousFunctionDidNotReturnUpdateBuilderObject;
                }
            });

            return $posts;
        } catch (EmptyQueryBuilderArguments|QueryError $exception) {
            throw new FailedToUpdatePages($exception);
        } catch (UpdateAnonymousFunctionDidNotReturnUpdateBuilderObject $exception) {
            throw $exception;
        }
    }
}
