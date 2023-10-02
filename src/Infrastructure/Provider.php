<?php

namespace Wordless\Infrastructure;

abstract class Provider
{
    public static function registerApiControllers(): array
    {
        return [];
    }

    public static function registerListeners(): array
    {
        return [];
    }

    public static function registerMenus(): array
    {
        return [];
    }

    public static function registerPostTypes(): array
    {
        return [];
    }

    public static function registerTaxonomies(): array
    {
        return [];
    }

    public static function unregisterActionListeners(): array
    {
        return [];
    }

    public static function unregisterFilterListeners(): array
    {
        return [];
    }
}
