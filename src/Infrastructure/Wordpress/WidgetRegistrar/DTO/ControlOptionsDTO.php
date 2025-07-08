<?php declare(strict_types=1);

namespace Wordless\Infrastructure\Wordpress\WidgetRegistrar\DTO;

class ControlOptionsDTO
{
    private int $admin_box_width; // Better do not change it. Use it carefully
    private string $base_id;

    public function setAdminBoxWidth(int $admin_box_width): static
    {
        $this->admin_box_width = $admin_box_width;

        return $this;
    }

    public function setBaseId(string $base_id): static
    {
        $this->base_id = $base_id;

        return $this;
    }

    public function toArray(): ?array
    {
        $return = [];

        if (isset($this->admin_box_width)) {
            $return['width'] = $this->admin_box_width;
        }

        if (isset($this->base_id)) {
            $return['id_base'] = $this->base_id;
        }

        return empty($return) ? null : $return;
    }
}
