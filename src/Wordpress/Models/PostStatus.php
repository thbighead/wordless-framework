<?php declare(strict_types=1);

namespace Wordless\Wordpress\Models;

use stdClass;
use Wordless\Application\Helpers\GetType;
use Wordless\Application\Libraries\PolymorphicConstructor\Contracts\IPolymorphicConstructor;
use Wordless\Application\Libraries\PolymorphicConstructor\Exceptions\ClassDoesNotImplementsPolymorphicConstructor;
use Wordless\Application\Libraries\PolymorphicConstructor\Exceptions\ConstructorNotImplemented;
use Wordless\Application\Libraries\PolymorphicConstructor\Traits\PolymorphicConstructorGuesser;
use Wordless\Application\Libraries\PolymorphicConstructor\Traits\PolymorphicConstructorGuesser\Exceptions\FailedToGuessConstructor;
use Wordless\Infrastructure\Wordpress\QueryBuilder\Exceptions\EmptyQueryBuilderArguments;
use Wordless\Wordpress\Models\PostStatus\Enums\StandardStatus;
use Wordless\Wordpress\Models\PostStatus\Exceptions\FailedToConstructPostStatus;
use Wordless\Wordpress\Models\PostStatus\Exceptions\UnknownArgument;
use Wordless\Wordpress\QueryBuilder\PostStatusQueryBuilder;

/**
 * @property-read bool $builtin
 * @property-read bool $date_floating
 * @property-read bool $excluded_from_search
 * @property-read bool $internal
 * @property-read string $label
 * @property-read array $label_count
 * @property-read bool $private
 * @property-read bool $protected
 * @property-read bool $public
 * @property-read bool $publicly_queryable
 * @property-read bool $shown_in_admin_all_list
 * @property-read bool $shown_in_admin_status_list
 */
readonly class PostStatus implements IPolymorphicConstructor
{
    use PolymorphicConstructorGuesser;

    public string $name;
    public string $slug;
    private stdClass $rawPostStatusObject;

    /**
     * @return array<string, static>
     * @throws FailedToConstructPostStatus
     */
    public static function all(): array
    {
        $all = [];

        try {
            foreach (PostStatusQueryBuilder::make()->get() as $post_status_slug => $postStatusObject) {
                $all[$post_status_slug] = new static($postStatusObject);
            }
        } catch (ClassDoesNotImplementsPolymorphicConstructor
        |ConstructorNotImplemented
        |EmptyQueryBuilderArguments
        |FailedToGuessConstructor $exception) {
            throw new FailedToConstructPostStatus(
                $post_status_slug ?? null,
                $postStatusObject ?? null,
                $exception
            );
        }

        return $all;
    }

    public static function constructorsDictionary(): array
    {
        return [
            1 => [
                GetType::STRING => '__constructFromSlug',
                stdClass::class => '__constructFromObject',
            ],
        ];
    }

    public function __constructFromSlug(string $post_status_slug): void
    {
        $this->slug = $this->name = $post_status_slug;
    }

    public function __constructFromObject(stdClass $postStatusObject): void
    {
        $this->slug = $this->name = $postStatusObject->name;
        $this->rawPostStatusObject = $postStatusObject;
    }

    /**
     * @param string $name
     * @return string|array|bool
     * @throws UnknownArgument
     */
    public function __get(string $name)
    {
        if (!isset($this->rawPostStatusObject)) {
            try {
                $this->rawPostStatusObject = PostStatusQueryBuilder::make()
                    ->whereSlug($this->slug)
                    ->first();
            } catch (EmptyQueryBuilderArguments) {
            }
        }

        return match ($name) {
            'builtin' => $this->rawPostStatusObject->_builtin
                ?? throw new UnknownArgument('raw _builtin'),
            'date_floating' => $this->rawPostStatusObject->date_floating
                ?? throw new UnknownArgument("raw $name"),
            'excluded_from_search' => $this->rawPostStatusObject->exclude_from_search
                ?? throw new UnknownArgument('raw exclude_from_search'),
            'internal' => $this->rawPostStatusObject->internal
                ?? throw new UnknownArgument("raw $name"),
            'label' => $this->rawPostStatusObject->label
                ?? throw new UnknownArgument("raw $name"),
            'label_count' => $this->rawPostStatusObject->label_count
                ?? throw new UnknownArgument("raw $name"),
            'private' => $this->rawPostStatusObject->private
                ?? throw new UnknownArgument("raw $name"),
            'protected' => $this->rawPostStatusObject->protected
                ?? throw new UnknownArgument("raw $name"),
            'public' => $this->rawPostStatusObject->public
                ?? throw new UnknownArgument("raw $name"),
            'publicly_queryable' => $this->rawPostStatusObject->publicly_queryable
                ?? throw new UnknownArgument("raw $name"),
            'shown_in_admin_all_list' => $this->rawPostStatusObject->show_in_admin_all_list
                ?? throw new UnknownArgument('raw show_in_admin_all_list'),
            'shown_in_admin_status_list' => $this->rawPostStatusObject->show_in_admin_status_list
                ?? throw new UnknownArgument('raw show_in_admin_status_list'),
            default => throw new UnknownArgument($name),
        };
    }

    public function is(PostStatus|StandardStatus|string $status): bool
    {
        if (!is_string($status)) {
            $status = $status->value ?? $status->name;
        }

        return $this->name === $status;
    }
}
