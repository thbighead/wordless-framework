<?php declare(strict_types=1);

namespace Wordless\Infrastructure\Wordpress;

use Wordless\Application\Libraries\Component\BaseComponent;
use Wordless\Infrastructure\Wordpress\EnqueueableAsset\Enums\StandardContext;
use WP_Widget;

abstract class Widget extends BaseComponent
{
    public function __construct(readonly private WP_Widget $wpWidget, StandardContext $context = StandardContext::no_context)
    {
        parent::__construct($context);
    }

    final public function html(array $widget_fields_values = [], array $registered_widget_properties = []): string
    {
        ob_start();
        $this->wpWidget->widget($registered_widget_properties, $widget_fields_values);
        $template = ob_get_contents();
        ob_end_clean();

        return $template;
    }
}
