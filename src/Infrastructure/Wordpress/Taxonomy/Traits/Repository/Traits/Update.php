<?php declare(strict_types=1);

namespace Wordless\Infrastructure\Wordpress\Taxonomy\Traits\Repository\Traits;

use Wordless\Infrastructure\Wordpress\Taxonomy\Traits\Repository\Traits\Update\UpdateBuilder;

trait Update
{
    public function buildEdit(): UpdateBuilder
    {
        return new UpdateBuilder($this->id(), $this->taxonomy);
    }
}
