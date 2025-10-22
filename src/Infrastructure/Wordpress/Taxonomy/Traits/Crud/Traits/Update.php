<?php declare(strict_types=1);

namespace Wordless\Infrastructure\Wordpress\Taxonomy\Traits\Crud\Traits;

use Wordless\Infrastructure\Wordpress\Taxonomy\Traits\Crud\Traits\Update\UpdateBuilder;

trait Update
{
    public function buildEdit(): UpdateBuilder
    {
        return new UpdateBuilder($this->id(), $this->taxonomy);
    }
}
