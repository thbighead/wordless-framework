<?php declare(strict_types=1);

namespace Wordless\Application\Helpers;

use Wordless\Infrastructure\Helper;

class Template extends Helper
{
    public static function includeTemplate(string $template_name, array $data = []): void
    {
        $template = locate_template(Str::finishWith($template_name, '.php'));

        if (empty($template)) {
            return;
        }

        unset($data['template']);

        if (!empty($data)) {
            extract($data);
        }

        include($template);
    }
}
