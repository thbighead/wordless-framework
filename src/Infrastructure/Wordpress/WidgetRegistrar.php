<?php declare(strict_types=1);

namespace Wordless\Infrastructure\Wordpress;

use Wordless\Infrastructure\Wordpress\WidgetRegistrar\AdminFormField;
use Wordless\Infrastructure\Wordpress\WidgetRegistrar\DTO\ControlOptionsDTO;
use Wordless\Infrastructure\Wordpress\WidgetRegistrar\DTO\SidebarOptionsDTO;
use WP_Widget;

abstract class WidgetRegistrar extends WP_Widget
{
    abstract protected function controlOptions(): ControlOptionsDTO;

    abstract protected function displayName(): string;

    abstract protected function id(): string;

    abstract protected function mountFrontEndHtml(
        array $widget_fields_values,
        array $registered_widget_properties
    ): string;

    abstract protected function sidebarOptions(): SidebarOptionsDTO;

    public static function register(): void
    {
        register_widget(static::class);
    }

    public function __construct()
    {
        parent::__construct(
            $this->id(),
            $this->displayName(),
            $this->sidebarOptions()->toArray(),
            $this->controlOptions()->toArray()
        );
    }

    final public function form($instance): void
    {
        echo $this->mountAdminFormFieldsHtml($instance);
    }

    final public function update($new_instance, $old_instance): array
    {
        return $this->resolveAdminFormSubmission($new_instance, $old_instance);
    }

    final public function widget($args, $instance): void
    {
        echo $this->mountFrontEndHtml($instance, $args);
    }

    /**
     * @return AdminFormField[]
     */
    protected function adminFormFields(): array
    {
        return [];
    }

    protected function resolveAdminFormSubmission(array $new_submitted_values, array $old_submitted_values): array
    {
        return parent::update($new_submitted_values, $old_submitted_values);
    }

    private function mountAdminFormFieldsHtml(array $old_submitted_values): string
    {
        if (empty($formFields = $this->adminFormFields())) {
            parent::form($old_submitted_values);

            return '';
        }

        $html = '';

        foreach ($formFields as $adminFormField) {
            $html .= $adminFormField->html();
        }

        return $html;
    }
}
