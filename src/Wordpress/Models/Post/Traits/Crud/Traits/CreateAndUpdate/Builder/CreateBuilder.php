<?php declare(strict_types=1);

namespace Wordless\Wordpress\Models\Post\Traits\Crud\Traits\CreateAndUpdate\Builder;

use Wordless\Infrastructure\Wordpress\CustomPost;
use Wordless\Wordpress\Models\Post\Traits\Crud\Traits\CreateAndUpdate\Builder;
use Wordless\Wordpress\Models\Post\Traits\Crud\Traits\CreateAndUpdate\Builder\Exceptions\WpInsertPostError;
use Wordless\Wordpress\Models\PostType;
use Wordless\Wordpress\Models\PostType\Enums\StandardType;

class CreateBuilder extends Builder
{
    public function __construct(string $title, StandardType|PostType|CustomPost|string $type)
    {
        parent::__construct(null, $title, $type);
    }

    /**
     * @param bool $firing_after_events
     * @return int
     * @throws WpInsertPostError
     */
    public function create(bool $firing_after_events = true): int
    {
        return $this->callWpInsertPost($firing_after_events);
    }
}
