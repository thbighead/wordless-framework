<?php declare(strict_types=1);

namespace Wordless\Wordpress\Models\Post\Contracts\BasePost\Traits\Crud\Traits;

use Wordless\Wordpress\Models\Post\Contracts\BasePost\Traits\Crud\Traits\CreateAndUpdate\Builder\CreateBuilder;
use Wordless\Wordpress\Models\Post\Contracts\BasePost\Traits\Crud\Traits\CreateAndUpdate\Builder\UpdateBuilder;

trait CreateAndUpdate
{
    public static function buildNew(string $title): CreateBuilder
    {
        return new CreateBuilder($title, static::TYPE_KEY);
    }

    public function buildEdit(): UpdateBuilder
    {
        return new UpdateBuilder($this->ID, $this->post_title, static::TYPE_KEY);
    }
}
