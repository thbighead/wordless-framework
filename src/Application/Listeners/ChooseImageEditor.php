<?php declare(strict_types=1);

namespace Wordless\Application\Listeners;

use Wordless\Application\Helpers\Config;
use Wordless\Application\Helpers\Config\Contracts\Subjectable\DTO\ConfigSubjectDTO\Exceptions\EmptyConfigKey;
use Wordless\Application\Helpers\ProjectPath\Exceptions\PathNotFoundException;
use Wordless\Application\Styles\AdminBarEnvironmentFlagStyle\Exceptions\FailedToRetrieveConfigFromWordpressConfigFile;
use Wordless\Infrastructure\Wordpress\Hook\Contracts\FilterHook;
use Wordless\Infrastructure\Wordpress\Listener\FilterListener;
use Wordless\Wordpress\Hook\Enums\Filter;

class ChooseImageEditor extends FilterListener
{
    final public const CONFIG_KEY_IMAGE_LIBRARY = 'image_library';
    final public const IMAGE_LIBRARY_CONFIG_VALUE_IMAGICK = 'imagick';
    final public const IMAGE_LIBRARY_CONFIG_VALUE_GD = 'gd';
    /**
     * The function which shall be executed during hook
     */
    protected const FUNCTION = 'useGdInsteadImagick';

    /**
     * Based on https://br.wordpress.org/support/topic/nao-consigo-fazer-upload-de-imagens-no-word-press/#post-12171660
     *
     * This solution should fix production problems with Imagick and Media uploads
     *
     * @param string[] $image_editors
     * @return string[]
     * @throws FailedToRetrieveConfigFromWordpressConfigFile
     */
    public static function useGdInsteadImagick(array $image_editors): array
    {
        $configured_library = Config::wordpressAdmin(
            static::CONFIG_KEY_IMAGE_LIBRARY,
            self::IMAGE_LIBRARY_CONFIG_VALUE_IMAGICK
        );

        if ($configured_library === self::IMAGE_LIBRARY_CONFIG_VALUE_GD) {
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
