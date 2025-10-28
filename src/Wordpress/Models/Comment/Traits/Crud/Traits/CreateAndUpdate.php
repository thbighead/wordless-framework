<?php declare(strict_types=1);

namespace Wordless\Wordpress\Models\Comment\Traits\Crud\Traits;

use Wordless\Wordpress\Models\Comment\Exceptions\InvalidPostModelNamespace;
use Wordless\Wordpress\Models\Comment\Traits\Crud\Traits\CreateAndUpdate\Builder\CreateBuilder;
use Wordless\Wordpress\Models\Comment\Traits\Crud\Traits\CreateAndUpdate\Builder\UpdateBuilder;

trait CreateAndUpdate
{
    public static function buildNew(): CreateBuilder
    {
        return new CreateBuilder;
    }

    /**
     * @return UpdateBuilder
     * @throws InvalidPostModelNamespace
     */
    public function buildEdit(): UpdateBuilder
    {
        return new UpdateBuilder($this->id(), $this->getPost());
    }
}
