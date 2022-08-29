<?php

namespace Wordless\Hookers;

use Wordless\Abstractions\AbstractHooker;
use Wordless\Exceptions\FailedToGenerateAcfGroupPhpFile;
use Wordless\Exceptions\PathNotFoundException;
use Wordless\Helpers\ProjectPath;
use WP_Post;

/**
 * Based on https://www.advancedcustomfields.com/resources/local-json/
 */
class AcfAutoGeneratePhpCodeOnSave extends AbstractHooker
{
    /**
     * WordPress action|filter number of arguments accepted by function
     */
    protected const ACCEPTED_NUMBER_OF_ARGUMENTS = 2;
    /**
     * The function which shall be executed during hook
     */
    protected const FUNCTION = 'generatePhpCode';
    /**
     * WordPress action|filter hook identification
     */
    protected const HOOK = 'wp_insert_post';

    public static function generatePhpCode(int $post_id, WP_Post $acfPost)
    {
        if ($acfPost->post_type !== 'acf-field-group' || $acfPost->post_status !== 'publish') {
            return;
        }

        try {
            self::generateAcfGroupPhpFile(self::loadAcfGroupWithFields($post_id));
        } catch (FailedToGenerateAcfGroupPhpFile|PathNotFoundException $exception) {
            error_log($exception->getMessage());
        }
    }

    /**
     * Based on ACF_Admin_Tool_Export::html_generate()
     * (plugins/advanced-custom-fields-pro/includes/admin/tools/class-acf-admin-tool-export.php)
     *
     * Generates the ACF group as PHP code.
     *
     * @param array $field_group
     * @return string
     */
    private static function generateAcfGroupPhpCode(array $field_group): string
    {
        // prevent default translation and fake __() within string
        acf_update_setting('l10n_var_export', true);

        $str_replace = [
            '  ' => "\t",
            "'!!__(!!\'" => "__('",
            "!!\', !!\'" => "', '",
            "!!\')!!'" => "')",
            'array (' => 'array(',
        ];
        $preg_replace = [
            '/([\t\r\n]+?)array/' => 'array',
            '/[0-9]+ => array/' => 'array',
        ];

        $code = var_export($field_group, true);
        // change double spaces to tabs
        $code = str_replace(array_keys($str_replace), array_values($str_replace), $code);
        // correctly formats "=> array("
        $code = preg_replace(array_keys($preg_replace), array_values($preg_replace), $code);

        return "<?php\r\n\r\nacf_add_local_field_group($code);\r\n";
    }

    /**
     * @param array $acf_group
     * @return void
     * @throws PathNotFoundException
     */
    private static function generateAcfGroupPhpFile(array $acf_group)
    {
        if (file_put_contents(
                $filepath = self::mountFilepath($acf_group['key'] ?? null),
                $file_content = self::generateAcfGroupPhpCode($acf_group)
            ) === false) {
            throw new FailedToGenerateAcfGroupPhpFile($filepath, $file_content);
        }
    }

    private static function loadAcfGroupWithFields(int $post_id): array
    {
        $field_group = acf_get_field_group($post_id);

        // load fields
        $field_group['fields'] = acf_get_fields($field_group);

        return acf_prepare_field_group_for_export($field_group);
    }

    /**
     * @param string $acf_group_unique_key
     * @return string
     * @throws PathNotFoundException
     */
    private static function mountFilepath(string $acf_group_unique_key): string
    {
        return ProjectPath::acfFieldGroups() . DIRECTORY_SEPARATOR . "$acf_group_unique_key.php";
    }
}
