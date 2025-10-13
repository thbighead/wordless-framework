<?php declare(strict_types=1);

namespace Wordless\Wordpress\Models\Category\Traits;

use Wordless\Application\Helpers\Arr;
use Wordless\Wordpress\Models\Category\Dictionary;

trait Repository
{
    /**
     * @return static[]
     */
    public static function all(): array
    {
        /** @var Dictionary $dictionary */
        $dictionary = static::getDictionary();
        $all = [];

        foreach ($dictionary->all() as $key => $categoryWpTerm) {
            $all[$key] = new static($categoryWpTerm);
        }

        return $all;
    }

    public static function noneCreated(): bool
    {
        if (empty($allCategories = static::all())) {
            return true;
        }

        /** @var static $firstCategory */
        $firstCategory = Arr::first($allCategories);

        return count($allCategories) === 1 && $firstCategory->isUncategorized();
    }
}
