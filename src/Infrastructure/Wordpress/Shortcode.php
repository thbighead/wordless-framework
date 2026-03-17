<?php declare(strict_types=1);

namespace Wordless\Infrastructure\Wordpress;

use Wordless\Application\Libraries\Component\BaseComponent;
use Wordless\Infrastructure\Wordpress\Registrar\ShortcodeRegistrar;

abstract class Shortcode extends BaseComponent
{
    abstract protected function registrar(): string|ShortcodeRegistrar;

    public static function do(string $tag, array $attributes = [], ?string $content = null): string
    {
        return do_shortcode(static::mountTagToDo($tag, $attributes, $content));
    }

    protected static function mountTagToDo(string $tag, array $attributes, ?string $content): string
    {
        $shortcode = "[$tag";
        $attributes_string = '';

        foreach ($attributes as $attribute_name => $attribute_value) {
            $attributes_string .= " $attribute_name='$attribute_value'";
        }

        $shortcode .= "$attributes_string]";

        if ($content !== null) {
            $shortcode .= "{$content}[/$tag]";
        }

        return $shortcode;
    }

    final public function html(array $attributes = [], ?string $content = null): string
    {
        return $this->registrar()::mountHtml($attributes, $content);
    }
}
