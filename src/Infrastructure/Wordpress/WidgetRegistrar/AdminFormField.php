<?php declare(strict_types=1);

namespace Wordless\Infrastructure\Wordpress\WidgetRegistrar;

abstract readonly class AdminFormField
{
    protected string $html_old_value;

    abstract public function html(): string;

    public function __construct(
        protected string $html_id_attribute,
        protected string $html_name_attribute,
        string $html_old_value = '',
    )
    {
        $this->html_old_value = esc_attr($html_old_value);
    }
}
