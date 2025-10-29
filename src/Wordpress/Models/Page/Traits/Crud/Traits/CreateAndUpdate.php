<?php declare(strict_types=1);

namespace Wordless\Wordpress\Models\Page\Traits\Crud\Traits;

use Wordless\Wordpress\Models\Page\Traits\Crud\Traits\CreateAndUpdate\Builder;
use Wordless\Wordpress\Models\Page\Traits\Crud\Traits\CreateAndUpdate\Builder\CreateBuilder;
use Wordless\Wordpress\Models\Page\Traits\Crud\Traits\CreateAndUpdate\Builder\UpdateBuilder;

trait CreateAndUpdate
{
    /**
     * @param string $title
     * @return CreateBuilder
     */
    public static function buildNew(string $title): Builder
    {
        return new CreateBuilder($title, static::TYPE_KEY);
    }

    /**
     * @return UpdateBuilder
     */
    public function buildEdit(): Builder
    {
        return new UpdateBuilder($this->ID, $this->post_title, static::TYPE_KEY);
    }
}
