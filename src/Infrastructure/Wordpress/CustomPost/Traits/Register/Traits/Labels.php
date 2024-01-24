<?php declare(strict_types=1);

namespace Wordless\Infrastructure\Wordpress\CustomPost\Traits\Register\Traits;

use InvalidArgumentException;
use Wordless\Application\Helpers\Str;

trait Labels
{
    public static function pluralName(): ?string
    {
        return null;
    }

    public static function singularName(): ?string
    {
        return null;
    }

    /**
     * https://developer.wordpress.org/reference/functions/get_post_type_labels/#description
     *
     * @return string[]
     */
    protected static function customizeLabels(): array
    {
        return [];
    }

    /**
     * https://developer.wordpress.org/reference/functions/get_post_type_labels/#description
     *
     * @return string[]
     * @throws InvalidArgumentException
     */
    private static function mountDefaultLabels(): array
    {
        $labels = [];
        $plural_name = static::pluralName();
        $singular_name = static::singularName();

        if ($plural_name !== null) {
            $labels['name'] = __($plural_name = Str::titleCase($plural_name));
            $labels['view_items'] = sprintf(__('View %s'), $plural_name);
            $labels['search_items'] = sprintf(__('Search %s'), $plural_name);
            $labels['not_found'] = sprintf(
                __('No %s found.'),
                $lower_cased_plural_name = Str::lower($plural_name)
            );
            $labels['not_found_in_trash'] = sprintf(__('No %s found in Trash.'), $lower_cased_plural_name);
            $labels['all_items'] = sprintf(__('All %s'), $plural_name);
            $labels['filter_items_list'] = sprintf(__('Filter %s list'), $lower_cased_plural_name);
            $labels['items_list_navigation'] = sprintf(__('%s list navigation'), $plural_name);
            $labels['items_list'] = sprintf(__('%s list'), $plural_name);
        }

        if ($singular_name !== null) {
            $labels['singular_name'] = __($singular_name = Str::titleCase($singular_name));
            $labels['add_new_item'] = sprintf(__('Add New %s'), $singular_name);
            $labels['edit_item'] = sprintf(__('Edit %s'), $singular_name);
            $labels['new_item'] = sprintf(__('New %s'), $singular_name);
            $labels['view_item'] = sprintf(__('View %s'), $singular_name);
            $labels['archives'] = sprintf(__('%s Archives'), $singular_name);
            $labels['attributes'] = sprintf(__('%s Attributes'), $singular_name);
            $labels['insert_into_item'] = sprintf(
                __('Insert into %s'),
                $lower_cased_singular_name = Str::lower($singular_name)
            );
            $labels['uploaded_to_this_item'] = sprintf(__('Uploaded to this %s'), $lower_cased_singular_name);
            $labels['item_published'] = sprintf(__('%s published.'), $singular_name);
            $labels['item_published_privately'] = sprintf(__('%s published privately.'), $singular_name);
            $labels['item_reverted_to_draft'] = sprintf(__('%s reverted to draft.'), $singular_name);
            $labels['item_scheduled'] = sprintf(__('%s scheduled.'), $singular_name);
            $labels['item_updated'] = sprintf(__('%s updated.'), $singular_name);
            $labels['item_link'] = sprintf(__('%s Link'), $singular_name);
            $labels['item_link_description'] = sprintf(__('A link to a %s.'), $singular_name);
        }

        return $labels;
    }
}
