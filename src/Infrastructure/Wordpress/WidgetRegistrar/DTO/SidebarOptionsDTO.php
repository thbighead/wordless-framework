<?php declare(strict_types=1);

namespace Wordless\Infrastructure\Wordpress\WidgetRegistrar\DTO;

class SidebarOptionsDTO
{
    private string $description;
    private string $html_classes;
    private bool $shown_instance_in_rest;

    public static function make(): static
    {
        return new static;
    }

    public function setDescription(string $description): static
    {
        $this->description = $description;

        return $this;
    }

    public function setHtmlClasses(string $html_classes): static
    {
        $this->html_classes = $html_classes;

        return $this;
    }

    public function showInRestApi(): static
    {
        $this->shown_instance_in_rest = true;

        return $this;
    }

    public function toArray(): array
    {
        $return = [];

        if (isset($this->description)) {
            $return['description'] = $this->description;
        }

        if (isset($this->html_classes)) {
            $return['classname'] = $this->html_classes;
        }

        if (isset($this->shown_instance_in_rest)) {
            $return['show_instance_in_rest'] = $this->shown_instance_in_rest;
        }

        return $return;
    }
}
