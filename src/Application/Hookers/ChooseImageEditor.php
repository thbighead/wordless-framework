<?php

namespace Wordless\Application\Hookers;

use Wordless\Application\Helpers\Config;
use Wordless\Exceptions\PathNotFoundException;
use Wordless\Infrastructure\Hooker;

class ChooseImageEditor extends Hooker
{
    public const IMAGE_LIBRARY_CONFIG_KEY = 'image_library';
    public const IMAGE_LIBRARY_CONFIG_VALUE_IMAGICK = 'imagick';
    public const IMAGE_LIBRARY_CONFIG_VALUE_GD = 'gd';
    /**
     * WordPress action|filter number of arguments accepted by function
     */
    protected const ACCEPTED_NUMBER_OF_ARGUMENTS = 1;
    /**
     * The function which shall be executed during hook
     */
    protected const FUNCTION = 'useGdInsteadImagick';
    /**
     * WordPress action|filter hook identification
     */
    protected const HOOK = 'wp_image_editors';
    /**
     * action or filter type (defines which method will be called: add_action or add_filter)
     */
    protected const TYPE = 'filter';

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
                'admin.' . static::IMAGE_LIBRARY_CONFIG_KEY,
                self::IMAGE_LIBRARY_CONFIG_VALUE_IMAGICK
            ) === self::IMAGE_LIBRARY_CONFIG_VALUE_GD) {
            return ['WP_Image_Editor_GD'];
        }

        return $image_editors;
    }
}
