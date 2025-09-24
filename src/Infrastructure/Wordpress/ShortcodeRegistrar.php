<?php declare(strict_types=1);

namespace Wordless\Infrastructure\Wordpress;

abstract class ShortcodeRegistrar
{
    abstract public function mountHtml(array $shortcode_tag_attributes, string $shortcode_tag_content): string;

    abstract protected function shortcodeTag(): string;

    public static function make(): static
    {
        return new static;
    }

    public function html(array $attributes = [], ?string $content = null): string
    {
        return do_shortcode($this->mountShortcode($attributes, $content));
    }

    public function register(): void
    {
        add_shortcode($this->shortcodeTag(), [$this, 'mountHtml']);
    }

    private function mountShortcode(array $attributes, ?string $content): string
    {
        $shortcode = "[{$this->shortcodeTag()}";
        $attributes_string = '';

        foreach ($attributes as $attribute_name => $attribute_value) {
            $attributes_string .= " $attribute_name='$attribute_value'";
        }

        $shortcode .= "$attributes_string]";

        if ($content !== null) {
            $shortcode .= "{$content}[/{$this->shortcodeTag()}]";
        }

        return $shortcode;
    }
}
