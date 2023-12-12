<?php

namespace Wordless\Infrastructure\Wordpress\Taxonomy\CustomTaxonomy\Traits\Register;

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
     * https://developer.wordpress.org/reference/functions/register_taxonomy/#arguments
     *
     * @return string[]
     */
    protected static function customizeLabels(): array
    {
        return [];
    }

    /**
     * https://developer.wordpress.org/reference/functions/register_taxonomy/#arguments
     *
     * @return string[]
     */
    private static function mountDefaultLabels(): array
    {
        $labels = [];
        $plural_name = static::pluralName();
        $singular_name = static::singularName();

        if ($plural_name !== null) {
            $labels['name'] = __($plural_name = Str::titleCase($plural_name));
            $labels['all_items'] = sprintf(__('All %s'), $plural_name);
            $labels['search_items'] = sprintf(__('Search %s'), $plural_name);
            $labels['popular_items'] = sprintf(__('Popular %s'), $plural_name);
            $labels['separate_items_with_commas'] = sprintf(
                __('Separate %s with commas'),
                $lower_cased_plural_name = Str::lower($plural_name)
            );
            $labels['add_or_remove_items'] = sprintf(__('Add or remove %s'), $lower_cased_plural_name);
            $labels['choose_from_most_used'] = sprintf(
                __('Choose from the most used %s'),
                $lower_cased_plural_name
            );
            $labels['not_found'] = sprintf(__('No %s found.'), $lower_cased_plural_name);
            $labels['back_to_items'] = sprintf(__('← Back to %s'), $lower_cased_plural_name);
        }

        if ($singular_name !== null) {
            $labels['singular_name'] = __($singular_name = Str::titleCase($singular_name));
            $labels['edit_item'] = sprintf(__('Edit %s'), $singular_name);
            $labels['view_item'] = sprintf(__('View %s'), $singular_name);
            $labels['update_item'] = sprintf(__('Update %s'), $singular_name);
            $labels['add_new_item'] = sprintf(__('Add New %s'), $singular_name);
            $labels['new_item_name'] = sprintf(__('New %s Name'), $singular_name);

            if (static::isHierarchical()) {
                $labels['parent_item'] = $parent_item = sprintf(__('Parent %s'), $singular_name);
                $labels['parent_item_colon'] = "$parent_item:";
            }
        }

        return $labels;
    }
}
