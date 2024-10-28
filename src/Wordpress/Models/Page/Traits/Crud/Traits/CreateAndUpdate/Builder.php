<?php declare(strict_types=1);

namespace Wordless\Wordpress\Models\Page\Traits\Crud\Traits\CreateAndUpdate;

use Wordless\Wordpress\Models\Post\Traits\Crud\Traits\CreateAndUpdate\Builder as PostBuilder;

abstract class Builder extends PostBuilder
{
    private ?string $template = null;

    public function template(string $template): static
    {
        $this->template = $template;

        return $this;
    }

    protected function mountPostArrayArguments(): array
    {
        $post_array = parent::mountPostArrayArguments();

        if (!empty($this->template)) {
            $post_array['page_template'] = $this->templatea;
        }

        return $post_array;
    }
}
