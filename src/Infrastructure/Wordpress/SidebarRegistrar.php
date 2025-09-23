<?php declare(strict_types=1);

namespace Wordless\Infrastructure\Wordpress;

abstract class SidebarRegistrar
{
    private const MARKER_1 = '%1$s';
    private const MARKER_2 = '%2$s';

    public static function register(): string
    {
        return register_sidebar(self::make()->toArray());
    }

    private static function make(): static
    {
        return new static;
    }

    protected function afterEachWidgetsHtml(): ?string
    {
        return null;
    }

    protected function afterHtml(): ?string
    {
        return null;
    }

    protected function afterTitleHtml(): ?string
    {
        return null;
    }

    protected function beforeEachWidgetsHtml(string $widget_html_id, string $widget_html_classes): ?string
    {
        return null;
    }

    protected function beforeHtml(string $html_id, string $html_classes): ?string
    {
        return null;
    }

    protected function beforeTitleHtml(): ?string
    {
        return null;
    }

    protected function htmlClasses(): ?string
    {
        return null;
    }

    protected function id(): ?string
    {
        return null;
    }

    protected function name(): ?string
    {
        return null;
    }

    protected function shouldShowInRestApi(): ?bool
    {
        return null;
    }

    protected function widgetsInterfaceDescription(): ?string
    {
        return null;
    }

    private function appendToArray(array &$return, string $key, ?string $value): static
    {
        if ($value !== null) {
            $return[$key] = $value;
        }

        return $this;
    }

    private function toArray(): array
    {
        $return = [];

        $this->appendToArray($return, 'name', $this->name())
            ->appendToArray($return, 'id', $this->id())
            ->appendToArray($return, 'description', $this->widgetsInterfaceDescription())
            ->appendToArray($return, 'class', $this->htmlClasses())
            ->appendToArray(
                $return,
                'before_widget',
                $this->beforeEachWidgetsHtml(self::MARKER_1, self::MARKER_2)
            )->appendToArray($return, 'after_widget', $this->afterEachWidgetsHtml())
            ->appendToArray($return, 'before_title', $this->beforeTitleHtml())
            ->appendToArray($return, 'after_title', $this->afterTitleHtml())
            ->appendToArray(
                $return,
                'before_sidebar',
                $this->beforeHtml(self::MARKER_1, self::MARKER_2)
            )->appendToArray($return, 'after_sidebar', $this->afterHtml());

        if (($show_in_rest = $this->shouldShowInRestApi()) !== null) {
            $return['show_in_rest'] = $show_in_rest;
        }

        return $return;
    }
}
