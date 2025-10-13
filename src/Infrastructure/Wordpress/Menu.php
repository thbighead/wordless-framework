<?php declare(strict_types=1);

namespace Wordless\Infrastructure\Wordpress;

use InvalidArgumentException;
use Wordless\Application\Helpers\Expect;
use Wordless\Application\Helpers\Str;
use Wordless\Infrastructure\Wordpress\Menu\Exceptions\MenuLocationHasNoMenuToRetrieveItems;
use Wordless\Wordpress\Models\MenuItem;
use Wordless\Wordpress\Models\Post\Exceptions\InitializingModelWithWrongPostType;
use Wordless\Wordpress\Models\PostType\Exceptions\PostTypeNotRegistered;
use WP_Post;

abstract class Menu
{
    /** @var array<string, int> $dictionary */
    private static array $dictionary;
    /** @var WP_Post[] $menu_items_hierarchy */
    protected array $menu_items_hierarchy;
    protected string $navigation_html;

    abstract public static function id(): string;

    abstract public static function name(): string;

    /**
     * @return array<string, int>
     */
    public static function getDictionary(): array
    {
        return self::$dictionary ?? self::$dictionary = get_nav_menu_locations();
    }

    public static function getLocalizedMenuId(): ?int
    {
        return self::getDictionary()[static::id()] ?? null;
    }

    /**
     * @throws InitializingModelWithWrongPostType
     * @throws InvalidArgumentException
     * @throws MenuLocationHasNoMenuToRetrieveItems
     * @throws PostTypeNotRegistered
     */
    public function __construct()
    {
        $this->menu_items_hierarchy = $this->mountItemsHierarchy();
        $this->mountHtmlCodes();
    }

    public function getNavigationHtml(): string
    {
        return $this->navigation_html;
    }

    protected function dropdownIcon(): string
    {
        return '';
    }

    /**
     * @return WP_Post[]
     * @throws MenuLocationHasNoMenuToRetrieveItems
     */
    protected function getItemsList(): array
    {
        $menu = wp_get_nav_menu_items(
            static::getLocalizedMenuId() ?? throw new MenuLocationHasNoMenuToRetrieveItems($this),
            ['output' => OBJECT]
        );

        return Expect::array($menu);
    }

    protected function mountHtmlCodes(): void
    {
        $this->navigation_html = '<nav><ul id="' . Str::lowerSnakeCase(static::id()) . '_list">';

        foreach ($this->menu_items_hierarchy as $rootMenuItem) {
            $this->navigation_html .=
                "<li{$rootMenuItem->mountLiClasses($rootMenuItem->hasChildren() ? ['root-parent-menu-item'] : [])}>{$this->mountRootMenuItemNavigationListItemHtmlContent($rootMenuItem)}</li>";
        }

        $this->navigation_html .= '</ul></nav>';
    }

    /**
     * @param MenuItem[] $menu_items_to_list
     * @return string
     */
    protected function mountMenuItemUlHtml(array $menu_items_to_list): string
    {
        $html = '';

        foreach ($menu_items_to_list as $menuItem) {
            $html .= "<li{$menuItem->mountLiClasses()}>";

            if ($menuItem->hasChildren()) {
                $html .=
                    "<h3>$menuItem->menu_item_title</h3>{$this->mountMenuItemUlHtml($menuItem->menu_item_children)}</li>";

                continue;
            }

            $html .= "{$menuItem->mountLinkHtml()}</li>";
        }

        return "<ul>$html</ul>";
    }

    protected function mountNavigationItemBodyHtmlOfRootMenuItemAsideDescription(MenuItem $rootMenuItem): string
    {
        $root_menu_item_children_body_aside_html = "<aside><h1>$rootMenuItem->menu_item_title</h1>";

        if ($rootMenuItem->hasDescription()) {
            $root_menu_item_children_body_aside_html .= "<p>$rootMenuItem->menu_item_description</p>";
        }

        return "$root_menu_item_children_body_aside_html</aside>";
    }

    protected function mountNonRootMenuItemsWithChildrenHtml(MenuItem $nonRootMenuItemWithChildren): string
    {
        return "<section><h2>$nonRootMenuItemWithChildren->menu_item_title</h2>{$this->mountMenuItemUlHtml($nonRootMenuItemWithChildren->menu_item_children)}</section>";
    }

    /**
     * @param MenuItem[] $childless_non_root_menu_items
     * @return string
     */
    protected function resolveChildlessNonRootMenuItemsHtml(array $childless_non_root_menu_items): string
    {
        if (empty($childless_non_root_menu_items)) {
            return '';
        }

        $childless_non_root_menu_items_html = '';

        foreach ($childless_non_root_menu_items as $nonRootMenuItem) {
            $childless_non_root_menu_items_html .=
                "<li{$nonRootMenuItem->mountLiClasses()}>{$nonRootMenuItem->mountLinkHtml()}</li>";
        }

        return "<ul>$childless_non_root_menu_items_html</ul>";
    }

    protected function wrapSubNavigationHtmlContent(string $sub_navigation_html_content): string
    {
        return "<nav>$sub_navigation_html_content</nav>";
    }

    /**
     * @param MenuItem $rootMenuItem
     * @return string
     */
    private function mountRootMenuItemNavigationListItemHtmlContent(MenuItem $rootMenuItem): string
    {
        if ($rootMenuItem->hasNoChildren()) {
            return $rootMenuItem->mountLinkHtml();
        }

        $dropdown_icon = empty($dropdown_icon = $this->dropdownIcon())
            ? ''
            : Str::startWith($dropdown_icon, ' ');

        return $rootMenuItem->mountLinkHtml("$rootMenuItem->menu_item_title$dropdown_icon")
            . $this->mountNavigationItemBodyHtmlOfRootMenuItem($rootMenuItem);
    }

    /**
     * @return MenuItem[]
     * @throws InitializingModelWithWrongPostType
     * @throws MenuLocationHasNoMenuToRetrieveItems
     * @throws PostTypeNotRegistered
     */
    private function mountItemsHierarchy(): array
    {
        $items_hierarchy = [];

        foreach ($this->getItemsList() as $menuItem) {
            $menuItem = new MenuItem($menuItem);
            $items_hierarchy[$menuItem->menu_item_parent_id][] = $menuItem;
        }

        return $this->mountTree($items_hierarchy, $items_hierarchy[0] ?? []);
    }

    private function mountNavigationItemBodyHtmlOfRootMenuItem(MenuItem $rootMenuItem): string
    {
        $root_menu_item_children_body_html = $this->mountNavigationItemBodyHtmlOfRootMenuItemAsideDescription(
            $rootMenuItem
        );

        $childless_menu_items = [];
        $children_ul_html_codes = '';

        foreach ($rootMenuItem->menu_item_children as $rootMenuItemChild) {
            if ($rootMenuItemChild->hasNoChildren()) {
                $childless_menu_items[] = $rootMenuItemChild;
                continue;
            }

            $children_ul_html_codes .= $this->mountNonRootMenuItemsWithChildrenHtml($rootMenuItemChild);
        }

        $root_menu_item_children_body_html .= $this->resolveChildlessNonRootMenuItemsHtml($childless_menu_items);

        return $this->wrapSubNavigationHtmlContent(
            "$root_menu_item_children_body_html$children_ul_html_codes"
        );
    }

    /**
     * @param array<int, MenuItem[]> $list
     * @param MenuItem[] $parents
     * @return array
     */
    private function mountTree(array &$list, array $parents): array
    {
        $tree = [];

        foreach ($parents as $menuItem) {
            if (isset($list[$menuItem->ID])) {
                $menuItem->setMenuItemChildren($this->mountTree($list, $list[$menuItem->ID]));
            }

            $tree[] = $menuItem;
        }

        return $tree;
    }
}
