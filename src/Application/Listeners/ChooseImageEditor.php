<?php declare(strict_types=1);

namespace Wordless\Application\Listeners;

use Wordless\Application\Helpers\Config;
use Wordless\Application\Helpers\ProjectPath\Exceptions\PathNotFoundException;
use Wordless\Infrastructure\Wordpress\Hook\Contracts\FilterHook;
use Wordless\Infrastructure\Wordpress\Listener\FilterListener;
use Wordless\Wordpress\Hook\Enums\Filter;

class ChooseImageEditor extends FilterListener
{
    public const IMAGE_LIBRARY_CONFIG_KEY = 'image_library';
    public const IMAGE_LIBRARY_CONFIG_VALUE_IMAGICK = 'imagick';
    public const IMAGE_LIBRARY_CONFIG_VALUE_GD = 'gd';
    /**
     * The function which shall be executed during hook
     */
    protected const FUNCTION = 'useGdInsteadImagick';


    /**
     * Based on https://br.wordpress.org/support/topic/nao-consigo-fazer-upload-de-imagens-no-word-press/#post-12171660
     *
     * This solution should fix production problems with Imagick and Media uploads
     *
     * @param array $image_editors
     * @return string[]
     * @throws PathNotFoundException
     */
    public static function useGdInsteadImagick(array $image_editors): array
    {
        if (Config::tryToGetOrDefault(
                'wordpress.admin.' . static::IMAGE_LIBRARY_CONFIG_KEY,
                self::IMAGE_LIBRARY_CONFIG_VALUE_IMAGICK
            ) === self::IMAGE_LIBRARY_CONFIG_VALUE_GD) {
            return ['WP_Image_Editor_GD'];
        }

        return $image_editors;
    }

    protected static function functionNumberOfArgumentsAccepted(): int
    {
        return 1;
    }

    protected static function hook(): FilterHook
    {
        return Filter::wp_image_editors;
    }
}
