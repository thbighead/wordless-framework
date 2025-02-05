<?php declare(strict_types=1);

namespace Wordless\Wordpress\Models;

use Wordless\Wordpress\Models\MenuItem\DTO\LinkDTO;
use Wordless\Wordpress\Models\MenuItem\DTO\ObjectDTO;
use Wordless\Wordpress\Models\MenuItem\DTO\TypeDTO;
use Wordless\Wordpress\Models\MenuItem\Enums\Type;
use Wordless\Wordpress\Models\Post\Exceptions\InitializingModelWithWrongPostType;
use Wordless\Wordpress\Models\PostType\Exceptions\PostTypeNotRegistered;
use Wordless\Wordpress\Models\Traits\WithAcfs\Exceptions\InvalidAcfFunction;
use WP_Post;

class MenuItem extends Post
{
    protected const TYPE_KEY = 'nav_menu_item';

    /** @var static[] $menu_item_children */
    public readonly array $menu_item_children;
    public readonly string $menu_item_description;
    public readonly array $menu_item_li_classes;
    public readonly int $menu_item_parent_id;
    public readonly string $menu_item_title;
    public readonly LinkDTO $menuItemLink;
    public readonly ObjectDTO $menuItemReferencedObject;
    public readonly TypeDTO $menuItemType;

    /**
     * @param WP_Post $menuItemPost
     * @param bool $with_acfs
     * @throws InitializingModelWithWrongPostType
     * @throws InvalidAcfFunction
     * @throws PostTypeNotRegistered
     */
    public function __construct(WP_Post $menuItemPost, bool $with_acfs = false)
    {
        parent::__construct($menuItemPost, $with_acfs);

        $this->menuItemType = new TypeDTO(
            Type::from($menuItemPost->type ?? ''),
            $menuItemPost->type_label ?? ''
        );
        $this->menu_item_description = $menuItemPost->description ?? '';
        $this->menu_item_li_classes = array_filter($menuItemPost->classes ?? []);
        $this->menu_item_parent_id = (int)($menuItemPost->menu_item_parent ?? 0);
        $this->menu_item_title = $menuItemPost->title ?? '';
        $this->menuItemLink = new LinkDTO(
            $menuItemPost->url ?? '#',
            !empty($menuItemPost->target),
            $menuItemPost->attr_title ?? null
        );
        $this->menuItemReferencedObject = new ObjectDTO(
            $menuItemPost->object ?? $this->menuItemType->type,
            $menuItemPost->object_id ?? null
        );
    }

    public function hasChildren(): bool
    {
        return !empty($this->menu_item_children ?? []);
    }

    public function hasDescription(): bool
    {
        return !empty($this->menu_item_description ?? '');
    }

    public function hasNoChildren(): bool
    {
        return !$this->hasChildren();
    }

    /**
     * @param string[] $additional_classes
     * @return string
     */
    public function mountLiClasses(array $additional_classes = []): string
    {
        if (empty($classes = array_merge($this->menu_item_li_classes, $additional_classes))) {
            return '';
        }

        return ' class="' . implode(' ', $classes) . '"';
    }

    public function mountLinkHtml(?string $custom_text = null): string
    {
        $link_text = $custom_text ?? $this->menu_item_title;
        $extra_attributes = '';

        if (!empty($this->menuItemLink->title_attribute)) {
            $extra_attributes .= " title='{$this->menuItemLink->title_attribute}'";
        }

        if ($this->menuItemLink->target_blank && $this->menuItemLink->url !== '#') {
            $extra_attributes .= ' target="_blank"';
        }

        return "<a href='{$this->menuItemLink->url}'$extra_attributes>$link_text</a>";
    }

    /**
     * @param static[] $menu_item_children
     */
    public function setMenuItemChildren(array $menu_item_children): void
    {
        if (!isset($this->menu_item_children)) {
            $this->menu_item_children = $menu_item_children;
        }
    }
}
