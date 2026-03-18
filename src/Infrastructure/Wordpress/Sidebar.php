<?php declare(strict_types=1);

namespace Wordless\Infrastructure\Wordpress;

use Wordless\Application\Helpers\Expect;
use Wordless\Application\Libraries\Component\StaticComponent;
use Wordless\Infrastructure\Wordpress\EnqueueableAsset\Enums\StandardContext;
use Wordless\Infrastructure\Wordpress\Sidebar\Exceptions\SidebarRegistryNotFound;
use Wordless\Infrastructure\Wordpress\Sidebar\Exceptions\WidgetRegistryNotFound;
use WP_Widget;

abstract class Sidebar extends StaticComponent
{
    abstract public static function id(): string;

    /** @var array<string, array<string, WP_Widget>> $sidebars_widgets */
    private static array $sidebars_widgets;
    /** @var array<string, static> $registered_sidebars */
    private static array $registered_sidebars;

    readonly public string $after_sidebar;
    readonly public string $after_title;
    readonly public string $after_widget;
    readonly public string $before_sidebar;
    readonly public string $before_title;
    readonly public string $before_widget;
    readonly public string $description;
    readonly public string $html_root_class;
    readonly public string $name;
    readonly public bool $show_in_rest_api;

    /**
     * @return array<string, static>
     */
    final public static function allRegistered(): array
    {
        if (!isset(self::$registered_sidebars)) {
            global $wp_registered_sidebars;

            self::$registered_sidebars = [];

            foreach ($wp_registered_sidebars as $sidebar_id => $sidebar_data) {
                self::$registered_sidebars[$sidebar_id] = new static;
            }
        }

        return self::$registered_sidebars;
    }

    /**
     * @param StandardContext $context
     * @throws SidebarRegistryNotFound
     */
    public function __construct(StandardContext $context = StandardContext::no_context)
    {
        parent::__construct($context);

        global $wp_registered_sidebars;

        if (!isset($wp_registered_sidebars[static::id()])) {
            throw new SidebarRegistryNotFound(static::id());
        }

        $this->setPropertiesFormRawData($wp_registered_sidebars[static::id()]);
    }

    /**
     * @return array<string, WP_Widget>|null
     * @throws WidgetRegistryNotFound
     */
    public function widgets(): ?array
    {
        if (!isset(self::$sidebars_widgets)) {
            global $wp_registered_widgets;

            self::$sidebars_widgets = [];

            foreach (wp_get_sidebars_widgets() as $sidebar_id => $widgets_ids) {
                foreach ($widgets_ids as $widget_id) {
                    if (!(($widget = $wp_registered_widgets[$widget_id]['callback'][0] ?? null) instanceof WP_Widget)) {
                        throw new WidgetRegistryNotFound($widget_id);
                    }

                    self::$sidebars_widgets[$sidebar_id][$widget_id] = $widget;
                }
            }
        }

        return self::$sidebars_widgets[static::id()] ?? null;
    }

    final protected function template(): string
    {
        ob_start();
        dynamic_sidebar(static::id());
        $template = ob_get_contents();
        ob_end_clean();

        return $template;
    }

    private function setPropertiesFormRawData(array $raw_data): void
    {
        $this->name = Expect::string($raw_data['name'] ?? '');
        $this->description = Expect::string($raw_data['description'] ?? '');
        $this->html_root_class = Expect::string($raw_data['class'] ?? '');
        $this->before_widget = Expect::string($raw_data['before_widget'] ?? '');
        $this->after_widget = Expect::string($raw_data['after_widget'] ?? '');
        $this->before_title = Expect::string($raw_data['before_title'] ?? '');
        $this->after_title = Expect::string($raw_data['after_title'] ?? '');
        $this->before_sidebar = Expect::string($raw_data['before_sidebar'] ?? '');
        $this->after_sidebar = Expect::string($raw_data['after_sidebar'] ?? '');
        $this->show_in_rest_api = Expect::boolean($raw_data['show_in_rest'] ?? false);
    }
}
