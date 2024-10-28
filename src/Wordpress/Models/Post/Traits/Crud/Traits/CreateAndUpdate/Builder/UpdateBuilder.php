<?php declare(strict_types=1);

namespace Wordless\Wordpress\Models\Post\Traits\Crud\Traits\CreateAndUpdate\Builder;

use Wordless\Infrastructure\Wordpress\CustomPost;
use Wordless\Wordpress\Models\Post\Traits\Crud\Traits\CreateAndUpdate\Builder;
use Wordless\Wordpress\Models\Post\Traits\Crud\Traits\CreateAndUpdate\Builder\Exceptions\WpInsertPostError;
use Wordless\Wordpress\Models\PostType;
use Wordless\Wordpress\Models\PostType\Enums\StandardType;

class UpdateBuilder extends Builder
{
    public function __construct(int $id, string $title, StandardType|PostType|CustomPost|string $type)
    {
        parent::__construct($id, $title, $type);
    }

    /**
     * @param bool $firing_after_events
     * @return int
     * @throws WpInsertPostError
     */
    public function update(bool $firing_after_events = true): int
    {
        return $this->callWpInsertPost($firing_after_events);
    }
}
