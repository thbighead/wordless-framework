<?php declare(strict_types=1);

namespace Wordless\Infrastructure\Wordpress;

use Wordless\Application\Libraries\Component\StaticComponent;

abstract class Sidebar extends StaticComponent
{
    abstract public static function id(): string;

    private static array $sidebars_widgets;

    final protected function template(): string
    {
        ob_start();
        dynamic_sidebar(static::id());
        $template = ob_get_contents();
        ob_end_clean();

        return $template;
    }

    /**
     * TODO testar com o Caio o retorno dessa xavasca (lá pelo MIT novo, talvez?)
     * @return array|null
     */
    public function widgets(): ?array
    {
        if (!isset(self::$sidebars_widgets)) {
            self::$sidebars_widgets = wp_get_sidebars_widgets();
        }

        return self::$sidebars_widgets[static::id()] ?? null;
    }
}
