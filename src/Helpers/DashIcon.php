<?php /** @noinspection PhpUnused */

namespace Wordless\Helpers;

class DashIcon
{
    private const DASHICON_PREFIX = 'dashicons-';

    /** @var string https://developer.wordpress.org/resource/dashicons/#menu */
    public const MENU_1 = self::DASHICON_PREFIX . 'menu';
    /** @var string https://developer.wordpress.org/resource/dashicons/#menu-alt */
    public const MENU_2 = self::DASHICON_PREFIX . 'menu-alt';
    /** @var string https://developer.wordpress.org/resource/dashicons/#menu-alt2 */
    public const MENU_3 = self::DASHICON_PREFIX . 'menu-alt2';
    /** @var string https://developer.wordpress.org/resource/dashicons/#menu-alt3 */
    public const MENU_4 = self::DASHICON_PREFIX . 'menu-alt3';
    /** @var string https://developer.wordpress.org/resource/dashicons/#admin-site */
    public const WORLD_AMERICA = self::DASHICON_PREFIX . 'admin-site';
    /** @var string https://developer.wordpress.org/resource/dashicons/#admin-site-alt */
    public const WORLD_EUROPE_AND_AFRICA = self::DASHICON_PREFIX . 'admin-site-alt';
    /** @var string https://developer.wordpress.org/resource/dashicons/#admin-site-alt2 */
    public const WORLD_EAST = self::DASHICON_PREFIX . 'admin-site-alt2';
    /** @var string https://developer.wordpress.org/resource/dashicons/#admin-site-alt3 */
    public const WORLD_GENERAL = self::DASHICON_PREFIX . 'admin-site-alt3';
    /** @var string https://developer.wordpress.org/resource/dashicons/#dashboard */
    public const DASHBOARD_1 = self::DASHICON_PREFIX . 'dashboard';
    /** @var string https://developer.wordpress.org/resource/dashicons/#admin-post */
    public const PIN_1 = self::DASHICON_PREFIX . 'admin-post';
    /** @var string https://developer.wordpress.org/resource/dashicons/#admin-media */
    public const MEDIA = self::DASHICON_PREFIX . 'admin-media';
    /** @var string https://developer.wordpress.org/resource/dashicons/#admin-links */
    public const HYPERLINK = self::DASHICON_PREFIX . 'admin-links';
    /** @var string https://developer.wordpress.org/resource/dashicons/#admin-page */
    public const COPY = self::DASHICON_PREFIX . 'admin-page';
    /** @var string https://developer.wordpress.org/resource/dashicons/#admin-comments */
    public const COMMENT_1 = self::DASHICON_PREFIX . 'admin-comments';
    /** @var string https://developer.wordpress.org/resource/dashicons/#admin-appearance */
    public const BRUSH = self::DASHICON_PREFIX . 'admin-appearance';
    /** @var string https://developer.wordpress.org/resource/dashicons/#admin-plugins */
    public const PLUG = self::DASHICON_PREFIX . 'admin-plugins';
    /** @var string https://developer.wordpress.org/resource/dashicons/#admin-users */
    public const USER_1 = self::DASHICON_PREFIX . 'admin-users';
    /** @var string https://developer.wordpress.org/resource/dashicons/#admin-tools */
    public const TOOL = self::DASHICON_PREFIX . 'admin-tools';
    /** @var string https://developer.wordpress.org/resource/dashicons/#admin-settings */
    public const SETTING = self::DASHICON_PREFIX . 'admin-settings';
    /** @var string https://developer.wordpress.org/resource/dashicons/#admin-network */
    public const KEY_1 = self::DASHICON_PREFIX . 'admin-network';
    /** @var string https://developer.wordpress.org/resource/dashicons/#admin-home */
    public const HOME = self::DASHICON_PREFIX . 'admin-home';
    /** @var string https://developer.wordpress.org/resource/dashicons/#admin-generic */
    public const GEAR = self::DASHICON_PREFIX . 'admin-generic';
    /** @var string https://developer.wordpress.org/resource/dashicons/#admin-collapse */
    public const CIRCLE_ARROW_LEFT = self::DASHICON_PREFIX . 'admin-collapse';
    /** @var string https://developer.wordpress.org/resource/dashicons/#filter */
    public const FILTER = self::DASHICON_PREFIX . 'filter';
    /** @var string https://developer.wordpress.org/resource/dashicons/#admin-customizer */
    public const PAINTBRUSH = self::DASHICON_PREFIX . 'admin-customizer';
    /** @var string https://developer.wordpress.org/resource/dashicons/#admin-multisite */
    public const HOUSES = self::DASHICON_PREFIX . 'admin-multisite';
    /** @var string https://developer.wordpress.org/resource/dashicons/#welcome-write-blog */
    public const WRITE = self::DASHICON_PREFIX . 'welcome-write-blog';
    /** @var string https://developer.wordpress.org/resource/dashicons/#welcome-add-page */
    public const ADD_PAGE = self::DASHICON_PREFIX . 'welcome-add-page';
    /** @var string https://developer.wordpress.org/resource/dashicons/#welcome-view-site */
    public const SCREEN_VISIBILITY = self::DASHICON_PREFIX . 'welcome-view-site';
    /** @var string https://developer.wordpress.org/resource/dashicons/#welcome-widgets-menus */
    public const DASHBOARD_2 = self::DASHICON_PREFIX . 'welcome-widgets-menus';
    /** @var string https://developer.wordpress.org/resource/dashicons/#welcome-comments */
    public const COMMENT_BLOCKED = self::DASHICON_PREFIX . 'welcome-comments';
    /** @var string https://developer.wordpress.org/resource/dashicons/#welcome-learn-more */
    public const GRADUATION_CAP = self::DASHICON_PREFIX . 'welcome-learn-more';
    /** @var string https://developer.wordpress.org/resource/dashicons/#format-aside */
    public const PAGE = self::DASHICON_PREFIX . 'format-aside';
    /** @var string https://developer.wordpress.org/resource/dashicons/#format-image */
    public const IMAGE = self::DASHICON_PREFIX . 'format-image';
    /** @var string https://developer.wordpress.org/resource/dashicons/#format-gallery */
    public const GALLERY = self::DASHICON_PREFIX . 'format-gallery';
    /** @var string https://developer.wordpress.org/resource/dashicons/#format-video */
    public const VIDEO_1 = self::DASHICON_PREFIX . 'format-video';
    /** @var string https://developer.wordpress.org/resource/dashicons/#format-status */
    public const COMMENT_DOTS = self::DASHICON_PREFIX . 'format-status';
    /** @var string https://developer.wordpress.org/resource/dashicons/#format-quote */
    public const QUOTE_1 = self::DASHICON_PREFIX . 'format-quote';
    /** @var string https://developer.wordpress.org/resource/dashicons/#format-chat */
    public const CHAT = self::DASHICON_PREFIX . 'format-chat';
    /** @var string https://developer.wordpress.org/resource/dashicons/#format-audio */
    public const AUDIO = self::DASHICON_PREFIX . 'format-audio';
    /** @var string https://developer.wordpress.org/resource/dashicons/#camera */
    public const CAMERA_1 = self::DASHICON_PREFIX . 'camera';
    /** @var string https://developer.wordpress.org/resource/dashicons/#camera-alt */
    public const CAMERA_2 = self::DASHICON_PREFIX . 'camera-alt';
    /** @var string https://developer.wordpress.org/resource/dashicons/#images-alt */
    public const IMAGES_1 = self::DASHICON_PREFIX . 'images-alt';
    /** @var string https://developer.wordpress.org/resource/dashicons/#images-alt2 */
    public const IMAGES_2 = self::DASHICON_PREFIX . 'images-alt2';
    /** @var string https://developer.wordpress.org/resource/dashicons/#video-alt */
    public const CAMERA_3 = self::DASHICON_PREFIX . 'video-alt';
    /** @var string https://developer.wordpress.org/resource/dashicons/#video-alt2 */
    public const CAMERA_4 = self::DASHICON_PREFIX . 'video-alt2';
    /** @var string https://developer.wordpress.org/resource/dashicons/#video-alt3 */
    public const PLAY_BUTTON = self::DASHICON_PREFIX . 'video-alt3';
    /** @var string https://developer.wordpress.org/resource/dashicons/#media-archive */
    public const FILE_COMPRESSED = self::DASHICON_PREFIX . 'media-archive';
    /** @var string https://developer.wordpress.org/resource/dashicons/#media-audio */
    public const FILE_AUDIO = self::DASHICON_PREFIX . 'media-audio';
    /** @var string https://developer.wordpress.org/resource/dashicons/#media-code */
    public const FILE_CODE = self::DASHICON_PREFIX . 'media-code';
    /** @var string https://developer.wordpress.org/resource/dashicons/#media-default */
    public const FILE = self::DASHICON_PREFIX . 'media-default';
    /** @var string https://developer.wordpress.org/resource/dashicons/#media-document */
    public const FILE_DOCUMENT = self::DASHICON_PREFIX . 'media-document';
    /** @var string https://developer.wordpress.org/resource/dashicons/#media-interactive */
    public const FILE_CERTIFICATION = self::DASHICON_PREFIX . 'media-interactive';
    /** @var string https://developer.wordpress.org/resource/dashicons/#media-spreadsheet */
    public const FILE_SPREADSHEET = self::DASHICON_PREFIX . 'media-spreadsheet';
    /** @var string https://developer.wordpress.org/resource/dashicons/#media-text */
    public const FILE_TEXT_1 = self::DASHICON_PREFIX . 'media-text';
    /** @var string https://developer.wordpress.org/resource/dashicons/#media-video */
    public const FILE_VIDEO = self::DASHICON_PREFIX . 'media-video';
    /** @var string https://developer.wordpress.org/resource/dashicons/#playlist-audio */
    public const PLAYLIST_AUDIO = self::DASHICON_PREFIX . 'playlist-audio';
    /** @var string https://developer.wordpress.org/resource/dashicons/#playlist-video */
    public const PLAYLIST_VIDEO = self::DASHICON_PREFIX . 'playlist-video';
    /** @var string https://developer.wordpress.org/resource/dashicons/#controls-play */
    public const PLAY = self::DASHICON_PREFIX . 'controls-play';
    /** @var string https://developer.wordpress.org/resource/dashicons/#controls-pause */
    public const PAUSE = self::DASHICON_PREFIX . 'controls-pause';
    /** @var string https://developer.wordpress.org/resource/dashicons/#controls-forward */
    public const FORWARD = self::DASHICON_PREFIX . 'controls-forward';
    /** @var string https://developer.wordpress.org/resource/dashicons/#controls-skipforward */
    public const SKIP_FORWARD = self::DASHICON_PREFIX . 'controls-skipforward';
    /** @var string https://developer.wordpress.org/resource/dashicons/#controls-back */
    public const BACK = self::DASHICON_PREFIX . 'controls-back';
    /** @var string https://developer.wordpress.org/resource/dashicons/#controls-skipback */
    public const SKIP_BACK = self::DASHICON_PREFIX . 'controls-skipback';
    /** @var string https://developer.wordpress.org/resource/dashicons/#controls-repeat */
    public const REPEAT = self::DASHICON_PREFIX . 'controls-repeat';
    /** @var string https://developer.wordpress.org/resource/dashicons/#controls-volumeon */
    public const VOLUME_ON = self::DASHICON_PREFIX . 'controls-volumeon';
    /** @var string https://developer.wordpress.org/resource/dashicons/#controls-volumeoff */
    public const VOLUME_OFF = self::DASHICON_PREFIX . 'controls-volumeoff';
    /** @var string https://developer.wordpress.org/resource/dashicons/#image-crop */
    public const CROP_IMAGE = self::DASHICON_PREFIX . 'image-crop';
    /** @var string https://developer.wordpress.org/resource/dashicons/#image-rotate */
    public const REFRESH = self::DASHICON_PREFIX . 'image-rotate';
    /** @var string https://developer.wordpress.org/resource/dashicons/#image-rotate-left */
    public const ROTATE_LEFT = self::DASHICON_PREFIX . 'image-rotate-left';
    /** @var string https://developer.wordpress.org/resource/dashicons/#image-rotate-right */
    public const ROTATE_RIGHT = self::DASHICON_PREFIX . 'image-rotate-right';
    /** @var string https://developer.wordpress.org/resource/dashicons/#image-flip-vertical */
    public const FLIP_VERTICAL = self::DASHICON_PREFIX . 'image-flip-vertical';
    /** @var string https://developer.wordpress.org/resource/dashicons/#image-flip-horizontal */
    public const FLIP_HORIZONTAL = self::DASHICON_PREFIX . 'image-flip-horizontal';
    /** @var string https://developer.wordpress.org/resource/dashicons/#image-filter */
    public const CIRCLES_TRIANGLE = self::DASHICON_PREFIX . 'image-filter';
    /** @var string https://developer.wordpress.org/resource/dashicons/#undo */
    public const UNDO = self::DASHICON_PREFIX . 'undo';
    /** @var string https://developer.wordpress.org/resource/dashicons/#redo */
    public const REDO = self::DASHICON_PREFIX . 'redo';
    /** @var string https://developer.wordpress.org/resource/dashicons/#database-add */
    public const DATABASE_ADD = self::DASHICON_PREFIX . 'database-add';
    /** @var string https://developer.wordpress.org/resource/dashicons/#database */
    public const DATABASE = self::DASHICON_PREFIX . 'database';
    /** @var string https://developer.wordpress.org/resource/dashicons/#database-export */
    public const DATABASE_EXPORT = self::DASHICON_PREFIX . 'database-export';
    /** @var string https://developer.wordpress.org/resource/dashicons/#database-import */
    public const DATABASE_IMPORT = self::DASHICON_PREFIX . 'database-import';
    /** @var string https://developer.wordpress.org/resource/dashicons/#database-remove */
    public const DATABASE_REMOVE = self::DASHICON_PREFIX . 'database-remove';
    /** @var string https://developer.wordpress.org/resource/dashicons/#database-view */
    public const DATABASE_CHECKED = self::DASHICON_PREFIX . 'database-view';
    /** @var string https://developer.wordpress.org/resource/dashicons/#align-full-width */
    public const ALIGN_TOP = self::DASHICON_PREFIX . 'align-full-width';
    /** @var string https://developer.wordpress.org/resource/dashicons/#align-pull-left */
    public const ALIGN_LEFT = self::DASHICON_PREFIX . 'align-pull-left';
    /** @var string https://developer.wordpress.org/resource/dashicons/#align-pull-right */
    public const ALIGN_RIGHT = self::DASHICON_PREFIX . 'align-pull-right';
    /** @var string https://developer.wordpress.org/resource/dashicons/#align-wide */
    public const ALIGN_CENTER_VERTICAL = self::DASHICON_PREFIX . 'align-wide';
    /** @var string https://developer.wordpress.org/resource/dashicons/#block-default */
    public const TOY_BLOCK = self::DASHICON_PREFIX . 'block-default';
    /** @var string https://developer.wordpress.org/resource/dashicons/#button */
    public const KEYBOARD_BUTTON = self::DASHICON_PREFIX . 'button';
    /** @var string https://developer.wordpress.org/resource/dashicons/#cloud-saved */
    public const CLOUD_CHECKED = self::DASHICON_PREFIX . 'cloud-saved';
    /** @var string https://developer.wordpress.org/resource/dashicons/#cloud-upload */
    public const CLOUD_UPLOAD = self::DASHICON_PREFIX . 'cloud-upload';
    /** @var string https://developer.wordpress.org/resource/dashicons/#columns */
    public const COLUMNS = self::DASHICON_PREFIX . 'columns';
    /** @var string https://developer.wordpress.org/resource/dashicons/#cover-image */
    public const ALIGN_TOP_IMAGE = self::DASHICON_PREFIX . 'cover-image';
    /** @var string https://developer.wordpress.org/resource/dashicons/#ellipsis */
    public const ELLIPSIS = self::DASHICON_PREFIX . 'ellipsis';
    /** @var string https://developer.wordpress.org/resource/dashicons/#embed-audio */
    public const EMBED_AUDIO = self::DASHICON_PREFIX . 'embed-audio';
    /** @var string https://developer.wordpress.org/resource/dashicons/#embed-generic */
    public const EMBED = self::DASHICON_PREFIX . 'embed-generic';
    /** @var string https://developer.wordpress.org/resource/dashicons/#embed-photo */
    public const EMBED_IMAGE = self::DASHICON_PREFIX . 'embed-photo';
    /** @var string https://developer.wordpress.org/resource/dashicons/#embed-post */
    public const EMBED_LOCALIZATION = self::DASHICON_PREFIX . 'embed-post';
    /** @var string https://developer.wordpress.org/resource/dashicons/#embed-video */
    public const EMBED_VIDEO = self::DASHICON_PREFIX . 'embed-video';
    /** @var string https://developer.wordpress.org/resource/dashicons/#exit */
    public const EXIT = self::DASHICON_PREFIX . 'exit';
    /** @var string https://developer.wordpress.org/resource/dashicons/#heading */
    public const HEADING = self::DASHICON_PREFIX . 'heading';
    /** @var string https://developer.wordpress.org/resource/dashicons/#html */
    public const HTML = self::DASHICON_PREFIX . 'html';
    /** @var string https://developer.wordpress.org/resource/dashicons/#info-outline */
    public const INFO_1 = self::DASHICON_PREFIX . 'info-outline';
    /** @var string https://developer.wordpress.org/resource/dashicons/#insert */
    public const ADD_1 = self::DASHICON_PREFIX . 'insert';
    /** @var string https://developer.wordpress.org/resource/dashicons/#insert-after */
    public const ADD_TO_TOP = self::DASHICON_PREFIX . 'insert-after';
    /** @var string https://developer.wordpress.org/resource/dashicons/#insert-before */
    public const ADD_TO_BOTTOM = self::DASHICON_PREFIX . 'insert-before';
    /** @var string https://developer.wordpress.org/resource/dashicons/#remove */
    public const REMOVE_1 = self::DASHICON_PREFIX . 'remove';
    /** @var string https://developer.wordpress.org/resource/dashicons/#saved */
    public const CHECKED_1 = self::DASHICON_PREFIX . 'saved';
    /** @var string https://developer.wordpress.org/resource/dashicons/#shortcode */
    public const SHORTCODE = self::DASHICON_PREFIX . 'shortcode';
    /** @var string https://developer.wordpress.org/resource/dashicons/#table-col-after */
    public const TABLE_ADD_COLUMN_RIGHT = self::DASHICON_PREFIX . 'table-col-after';
    /** @var string https://developer.wordpress.org/resource/dashicons/#table-col-before */
    public const TABLE_ADD_COLUMN_LEFT = self::DASHICON_PREFIX . 'table-col-before';
    /** @var string https://developer.wordpress.org/resource/dashicons/#table-col-delete */
    public const TABLE_REMOVE_COLUMN = self::DASHICON_PREFIX . 'table-col-delete';
    /** @var string https://developer.wordpress.org/resource/dashicons/#table-row-after */
    public const TABLE_ADD_ROW_TO_BOTTOM = self::DASHICON_PREFIX . 'table-row-after';
    /** @var string https://developer.wordpress.org/resource/dashicons/#table-row-before */
    public const TABLE_ADD_ROW_TO_TOP = self::DASHICON_PREFIX . 'table-row-before';
    /** @var string https://developer.wordpress.org/resource/dashicons/#table-row-delete */
    public const TABLE_REMOVE_ROW = self::DASHICON_PREFIX . 'table-row-delete';
    /** @var string https://developer.wordpress.org/resource/dashicons/#editor-bold */
    public const BOLD = self::DASHICON_PREFIX . 'editor-bold';
    /** @var string https://developer.wordpress.org/resource/dashicons/#editor-italic */
    public const ITALIC = self::DASHICON_PREFIX . 'editor-italic';
    /** @var string https://developer.wordpress.org/resource/dashicons/#editor-ul */
    public const LIST_UNORDERED = self::DASHICON_PREFIX . 'editor-ul';
    /** @var string https://developer.wordpress.org/resource/dashicons/#editor-ol */
    public const LIST_ORDERED = self::DASHICON_PREFIX . 'editor-ol';
    /** @var string https://developer.wordpress.org/resource/dashicons/#editor-ol-rtl */
    public const LIST_ORDERED_RTL = self::DASHICON_PREFIX . 'editor-ol-rtl';
    /** @var string https://developer.wordpress.org/resource/dashicons/#editor-quote */
    public const QUOTE_2 = self::DASHICON_PREFIX . 'editor-quote';
    /** @var string https://developer.wordpress.org/resource/dashicons/#editor-alignleft */
    public const TEXT_ALIGN_LEFT = self::DASHICON_PREFIX . 'editor-alignleft';
    /** @var string https://developer.wordpress.org/resource/dashicons/#editor-aligncenter */
    public const TEXT_ALIGN_CENTER = self::DASHICON_PREFIX . 'editor-aligncenter';
    /** @var string https://developer.wordpress.org/resource/dashicons/#editor-alignright */
    public const TEXT_ALIGN_RIGHT = self::DASHICON_PREFIX . 'editor-alignright';
    /** @var string https://developer.wordpress.org/resource/dashicons/#editor-insertmore */
    public const TEXT_LINEBREAK = self::DASHICON_PREFIX . 'editor-insertmore';
    /** @var string https://developer.wordpress.org/resource/dashicons/#editor-spellcheck */
    public const SPELLCHECK = self::DASHICON_PREFIX . 'editor-spellcheck';
    /** @var string https://developer.wordpress.org/resource/dashicons/#editor-expand */
    public const EXPAND = self::DASHICON_PREFIX . 'editor-expand';
    /** @var string https://developer.wordpress.org/resource/dashicons/#editor-contract */
    public const RETRACT = self::DASHICON_PREFIX . 'editor-contract';
    /** @var string https://developer.wordpress.org/resource/dashicons/#editor-kitchensink */
    public const BLOCKS_WRAPPED = self::DASHICON_PREFIX . 'editor-kitchensink';
    /** @var string https://developer.wordpress.org/resource/dashicons/#editor-underline */
    public const UNDERLINE = self::DASHICON_PREFIX . 'editor-underline';
    /** @var string https://developer.wordpress.org/resource/dashicons/#editor-justify */
    public const TEXT_ALIGN_JUSTIFY = self::DASHICON_PREFIX . 'editor-justify';
    /** @var string https://developer.wordpress.org/resource/dashicons/#editor-textcolor */
    public const FONTCOLOR = self::DASHICON_PREFIX . 'editor-textcolor';
    /** @var string https://developer.wordpress.org/resource/dashicons/#editor-paste-word */
    public const PASTE_WORD = self::DASHICON_PREFIX . 'editor-paste-word';
    /** @var string https://developer.wordpress.org/resource/dashicons/#editor-paste-text */
    public const PASTE_TEXT = self::DASHICON_PREFIX . 'editor-paste-text';
    /** @var string https://developer.wordpress.org/resource/dashicons/#editor-removeformatting */
    public const ERASER = self::DASHICON_PREFIX . 'editor-removeformatting';
    /** @var string https://developer.wordpress.org/resource/dashicons/#editor-video */
    public const VIDEO_2 = self::DASHICON_PREFIX . 'editor-video';
    /** @var string https://developer.wordpress.org/resource/dashicons/#editor-customchar */
    public const OMEGA = self::DASHICON_PREFIX . 'editor-customchar';
    /** @var string https://developer.wordpress.org/resource/dashicons/#editor-outdent */
    public const UNDO_INDENT = self::DASHICON_PREFIX . 'editor-outdent';
    /** @var string https://developer.wordpress.org/resource/dashicons/#editor-indent */
    public const INDENT = self::DASHICON_PREFIX . 'editor-indent';
    /** @var string https://developer.wordpress.org/resource/dashicons/#editor-help */
    public const HELP = self::DASHICON_PREFIX . 'editor-help';
    /** @var string https://developer.wordpress.org/resource/dashicons/#editor-strikethrough */
    public const STRIKETHROUGH = self::DASHICON_PREFIX . 'editor-strikethrough';
    /** @var string https://developer.wordpress.org/resource/dashicons/#editor-unlink */
    public const UNLINK = self::DASHICON_PREFIX . 'editor-unlink';
    /** @var string https://developer.wordpress.org/resource/dashicons/#editor-rtl */
    public const RTL = self::DASHICON_PREFIX . 'editor-rtl';
    /** @var string https://developer.wordpress.org/resource/dashicons/#editor-ltr */
    public const LTR = self::DASHICON_PREFIX . 'editor-ltr';
    /** @var string https://developer.wordpress.org/resource/dashicons/#editor-break */
    public const ENTER = self::DASHICON_PREFIX . 'editor-break';
    /** @var string https://developer.wordpress.org/resource/dashicons/#editor-code */
    public const CODE = self::DASHICON_PREFIX . 'editor-code';
    /** @var string https://developer.wordpress.org/resource/dashicons/#editor-table */
    public const TEXT_COLUMNS = self::DASHICON_PREFIX . 'editor-table';
    /** @var string https://developer.wordpress.org/resource/dashicons/#align-left */
    public const ALIGN_LEFT_TO_TEXT = self::DASHICON_PREFIX . 'align-left';
    /** @var string https://developer.wordpress.org/resource/dashicons/#align-right */
    public const ALIGN_RIGHT_TO_TEXT = self::DASHICON_PREFIX . 'align-right';
    /** @var string https://developer.wordpress.org/resource/dashicons/#align-center */
    public const ALIGN_BLOCK_CENTER_TO_TEXT = self::DASHICON_PREFIX . 'align-center';
    /** @var string https://developer.wordpress.org/resource/dashicons/#align-none */
    public const ALIGN_BLOCK_LEFT_TO_TEXT = self::DASHICON_PREFIX . 'align-none';
    /** @var string https://developer.wordpress.org/resource/dashicons/#lock */
    public const LOCK = self::DASHICON_PREFIX . 'lock';
    /** @var string https://developer.wordpress.org/resource/dashicons/#unlock */
    public const UNLOCK = self::DASHICON_PREFIX . 'unlock';
    /** @var string https://developer.wordpress.org/resource/dashicons/#calendar */
    public const CALENDAR_1 = self::DASHICON_PREFIX . 'calendar';
    /** @var string https://developer.wordpress.org/resource/dashicons/#calendar-alt */
    public const CALENDAR_2 = self::DASHICON_PREFIX . 'calendar-alt';
    /** @var string https://developer.wordpress.org/resource/dashicons/#visibility */
    public const VISIBLE = self::DASHICON_PREFIX . 'visibility';
    /** @var string https://developer.wordpress.org/resource/dashicons/#hidden */
    public const HIDDEN = self::DASHICON_PREFIX . 'hidden';
    /** @var string https://developer.wordpress.org/resource/dashicons/#post-status */
    public const KEY_2 = self::DASHICON_PREFIX . 'post-status';
    /** @var string https://developer.wordpress.org/resource/dashicons/#edit */
    public const PENCIL_1 = self::DASHICON_PREFIX . 'edit';
    /** @var string https://developer.wordpress.org/resource/dashicons/#trash */
    public const TRASH = self::DASHICON_PREFIX . 'trash';
    /** @var string https://developer.wordpress.org/resource/dashicons/#sticky */
    public const PIN_2 = self::DASHICON_PREFIX . 'sticky';
    /** @var string https://developer.wordpress.org/resource/dashicons/#external */
    public const EXTERNAL_1 = self::DASHICON_PREFIX . 'external';
    /** @var string https://developer.wordpress.org/resource/dashicons/#arrow-up */
    public const ARROW_UP_1 = self::DASHICON_PREFIX . 'arrow-up';
    /** @var string https://developer.wordpress.org/resource/dashicons/#arrow-down */
    public const ARROW_DOWN_1 = self::DASHICON_PREFIX . 'arrow-down';
    /** @var string https://developer.wordpress.org/resource/dashicons/#arrow-right */
    public const ARROW_RIGHT_1 = self::DASHICON_PREFIX . 'arrow-right';
    /** @var string https://developer.wordpress.org/resource/dashicons/#arrow-left */
    public const ARROW_LEFT_1 = self::DASHICON_PREFIX . 'arrow-left';
    /** @var string https://developer.wordpress.org/resource/dashicons/#arrow-up-alt */
    public const ARROW_UP_2 = self::DASHICON_PREFIX . 'arrow-up-alt';
    /** @var string https://developer.wordpress.org/resource/dashicons/#arrow-down-alt */
    public const ARROW_DOWN_2 = self::DASHICON_PREFIX . 'arrow-down-alt';
    /** @var string https://developer.wordpress.org/resource/dashicons/#arrow-right-alt */
    public const ARROW_RIGHT_2 = self::DASHICON_PREFIX . 'arrow-right-alt';
    /** @var string https://developer.wordpress.org/resource/dashicons/#arrow-left-alt */
    public const ARROW_LEFT_2 = self::DASHICON_PREFIX . 'arrow-left-alt';
    /** @var string https://developer.wordpress.org/resource/dashicons/#arrow-up-alt2 */
    public const ARROW_UP_3 = self::DASHICON_PREFIX . 'arrow-up-alt2';
    /** @var string https://developer.wordpress.org/resource/dashicons/#arrow-down-alt2 */
    public const ARROW_DOWN_3 = self::DASHICON_PREFIX . 'arrow-down-alt2';
    /** @var string https://developer.wordpress.org/resource/dashicons/#arrow-right-alt2 */
    public const ARROW_RIGHT_3 = self::DASHICON_PREFIX . 'arrow-right-alt2';
    /** @var string https://developer.wordpress.org/resource/dashicons/#arrow-left-alt2 */
    public const ARROW_LEFT_3 = self::DASHICON_PREFIX . 'arrow-left-alt2';
    /** @var string https://developer.wordpress.org/resource/dashicons/#sort */
    public const UP_DOWN = self::DASHICON_PREFIX . 'sort';
    /** @var string https://developer.wordpress.org/resource/dashicons/#leftright */
    public const LEFT_RIGHT = self::DASHICON_PREFIX . 'leftright';
    /** @var string https://developer.wordpress.org/resource/dashicons/#randomize */
    public const RANDOMIZE = self::DASHICON_PREFIX . 'randomize';
    /** @var string https://developer.wordpress.org/resource/dashicons/#list-view */
    public const LIST_VIEW_1 = self::DASHICON_PREFIX . 'list-view';
    /** @var string https://developer.wordpress.org/resource/dashicons/#excerpt-view */
    public const LIST_VIEW_2 = self::DASHICON_PREFIX . 'excerpt-view';
    /** @var string https://developer.wordpress.org/resource/dashicons/#grid-view */
    public const GRID_VIEW = self::DASHICON_PREFIX . 'grid-view';
    /** @var string https://developer.wordpress.org/resource/dashicons/#move */
    public const MOVE = self::DASHICON_PREFIX . 'move';
    /** @var string https://developer.wordpress.org/resource/dashicons/#share */
    public const SHARE_1 = self::DASHICON_PREFIX . 'share';
    /** @var string https://developer.wordpress.org/resource/dashicons/#share-alt */
    public const TRADE = self::DASHICON_PREFIX . 'share-alt';
    /** @var string https://developer.wordpress.org/resource/dashicons/#share-alt2 */
    public const SHARE_2 = self::DASHICON_PREFIX . 'share-alt2';
    /** @var string https://developer.wordpress.org/resource/dashicons/#rss */
    public const RSS = self::DASHICON_PREFIX . 'rss';
    /** @var string https://developer.wordpress.org/resource/dashicons/#email */
    public const EMAIL_1 = self::DASHICON_PREFIX . 'email';
    /** @var string https://developer.wordpress.org/resource/dashicons/#email-alt */
    public const EMAIL_2 = self::DASHICON_PREFIX . 'email-alt';
    /** @var string https://developer.wordpress.org/resource/dashicons/#email-alt2 */
    public const EMAIL_3 = self::DASHICON_PREFIX . 'email-alt2';
    /** @var string https://developer.wordpress.org/resource/dashicons/#networking */
    public const NETWORK_1 = self::DASHICON_PREFIX . 'networking';
    /** @var string https://developer.wordpress.org/resource/dashicons/#amazon */
    public const LOGO_AMAZON = self::DASHICON_PREFIX . 'amazon';
    /** @var string https://developer.wordpress.org/resource/dashicons/#facebook */
    public const LOGO_FACEBOOK_1 = self::DASHICON_PREFIX . 'facebook';
    /** @var string https://developer.wordpress.org/resource/dashicons/#facebook-alt */
    public const LOGO_FACEBOOK_2 = self::DASHICON_PREFIX . 'facebook-alt';
    /** @var string https://developer.wordpress.org/resource/dashicons/#google */
    public const LOGO_GOOGLE = self::DASHICON_PREFIX . 'google';
    /** @var string https://developer.wordpress.org/resource/dashicons/#instagram */
    public const LOGO_INSTAGRAM = self::DASHICON_PREFIX . 'instagram';
    /** @var string https://developer.wordpress.org/resource/dashicons/#linkedin */
    public const LOGO_LINKEDIN = self::DASHICON_PREFIX . 'linkedin';
    /** @var string https://developer.wordpress.org/resource/dashicons/#pinterest */
    public const LOGO_PINTEREST = self::DASHICON_PREFIX . 'pinterest';
    /** @var string https://developer.wordpress.org/resource/dashicons/#podio */
    public const LOGO_PODIO = self::DASHICON_PREFIX . 'podio';
    /** @var string https://developer.wordpress.org/resource/dashicons/#spotify */
    public const LOGO_SPOTIFY = self::DASHICON_PREFIX . 'spotify';
    /** @var string https://developer.wordpress.org/resource/dashicons/#twitch */
    public const LOGO_TWITCH = self::DASHICON_PREFIX . 'twitch';
    /** @var string https://developer.wordpress.org/resource/dashicons/#twitter */
    public const LOGO_TWITTER_1 = self::DASHICON_PREFIX . 'twitter';
    /** @var string https://developer.wordpress.org/resource/dashicons/#twitter-alt */
    public const LOGO_TWITTER_2 = self::DASHICON_PREFIX . 'twitter-alt';
    /** @var string https://developer.wordpress.org/resource/dashicons/#whatsapp */
    public const LOGO_WHATSAPP = self::DASHICON_PREFIX . 'whatsapp';
    /** @var string https://developer.wordpress.org/resource/dashicons/#xing */
    public const LOGO_XING = self::DASHICON_PREFIX . 'xing';
    /** @var string https://developer.wordpress.org/resource/dashicons/#youtube */
    public const LOGO_YOUTUBE = self::DASHICON_PREFIX . 'youtube';
    /** @var string https://developer.wordpress.org/resource/dashicons/#hammer */
    public const HAMMER = self::DASHICON_PREFIX . 'hammer';
    /** @var string https://developer.wordpress.org/resource/dashicons/#art */
    public const PALETTE = self::DASHICON_PREFIX . 'art';
    /** @var string https://developer.wordpress.org/resource/dashicons/#migrate */
    public const EXTERNAL_2 = self::DASHICON_PREFIX . 'migrate';
    /** @var string https://developer.wordpress.org/resource/dashicons/#performance */
    public const PERFORMANCE = self::DASHICON_PREFIX . 'performance';
    /** @var string https://developer.wordpress.org/resource/dashicons/#universal-access */
    public const ACCESSIBILITY_1 = self::DASHICON_PREFIX . 'universal-access';
    /** @var string https://developer.wordpress.org/resource/dashicons/#universal-access-alt */
    public const ACCESSIBILITY_2 = self::DASHICON_PREFIX . 'universal-access-alt';
    /** @var string https://developer.wordpress.org/resource/dashicons/#tickets */
    public const WORDPRESS_TICKETS = self::DASHICON_PREFIX . 'tickets';
    /** @var string https://developer.wordpress.org/resource/dashicons/#nametag */
    public const NAME_TAG = self::DASHICON_PREFIX . 'nametag';
    /** @var string https://developer.wordpress.org/resource/dashicons/#clipboard */
    public const CLIPBOARD = self::DASHICON_PREFIX . 'clipboard';
    /** @var string https://developer.wordpress.org/resource/dashicons/#heart */
    public const HEART = self::DASHICON_PREFIX . 'heart';
    /** @var string https://developer.wordpress.org/resource/dashicons/#megaphone */
    public const MEGAPHONE = self::DASHICON_PREFIX . 'megaphone';
    /** @var string https://developer.wordpress.org/resource/dashicons/#schedule */
    public const GRID = self::DASHICON_PREFIX . 'schedule';
    /** @var string https://developer.wordpress.org/resource/dashicons/#tide */
    public const TIDE = self::DASHICON_PREFIX . 'tide';
    /** @var string https://developer.wordpress.org/resource/dashicons/#rest-api */
    public const NETWORK_2 = self::DASHICON_PREFIX . 'rest-api';
    /** @var string https://developer.wordpress.org/resource/dashicons/#code-standards */
    public const STANDARD_CHECKER = self::DASHICON_PREFIX . 'code-standards';
    /** @var string https://developer.wordpress.org/resource/dashicons/#buddicons-activity */
    public const PUPPY = self::DASHICON_PREFIX . 'buddicons-activity';
    /** @var string https://developer.wordpress.org/resource/dashicons/#buddicons-bbpress-logo */
    public const LOGO_BBPRESS = self::DASHICON_PREFIX . 'buddicons-bbpress-logo';
    /** @var string https://developer.wordpress.org/resource/dashicons/#buddicons-buddypress-logo */
    public const LOGO_BUDDYPRESS = self::DASHICON_PREFIX . 'buddicons-buddypress-logo';
    /** @var string https://developer.wordpress.org/resource/dashicons/#buddicons-community */
    public const CAKE = self::DASHICON_PREFIX . 'buddicons-community';
    /** @var string https://developer.wordpress.org/resource/dashicons/#buddicons-forums */
    public const HIVE = self::DASHICON_PREFIX . 'buddicons-forums';
    /** @var string https://developer.wordpress.org/resource/dashicons/#buddicons-friends */
    public const CANDLES = self::DASHICON_PREFIX . 'buddicons-friends';
    /** @var string https://developer.wordpress.org/resource/dashicons/#buddicons-groups */
    public const BALLOONS = self::DASHICON_PREFIX . 'buddicons-groups';
    /** @var string https://developer.wordpress.org/resource/dashicons/#buddicons-pm */
    public const OPENED_MAIL = self::DASHICON_PREFIX . 'buddicons-pm';
    /** @var string https://developer.wordpress.org/resource/dashicons/#buddicons-replies */
    public const BEE = self::DASHICON_PREFIX . 'buddicons-replies';
    /** @var string https://developer.wordpress.org/resource/dashicons/#buddicons-topics */
    public const HONEY_STICK = self::DASHICON_PREFIX . 'buddicons-topics';
    /** @var string https://developer.wordpress.org/resource/dashicons/#buddicons-tracking */
    public const PARTY_HAT = self::DASHICON_PREFIX . 'buddicons-tracking';
    /** @var string https://developer.wordpress.org/resource/dashicons/#wordpress */
    public const LOGO_WORDPRESS_1 = self::DASHICON_PREFIX . 'wordpress';
    /** @var string https://developer.wordpress.org/resource/dashicons/#wordpress-alt */
    public const LOGO_WORDPRESS_2 = self::DASHICON_PREFIX . 'wordpress-alt';
    /** @var string https://developer.wordpress.org/resource/dashicons/#pressthis */
    public const FILE_DROPPER = self::DASHICON_PREFIX . 'pressthis';
    /** @var string https://developer.wordpress.org/resource/dashicons/#update */
    public const UPDATE_1 = self::DASHICON_PREFIX . 'update';
    /** @var string https://developer.wordpress.org/resource/dashicons/#update-alt */
    public const UPDATE_2 = self::DASHICON_PREFIX . 'update-alt';
    /** @var string https://developer.wordpress.org/resource/dashicons/#screenoptions */
    public const BLOCKS_1 = self::DASHICON_PREFIX . 'screenoptions';
    /** @var string https://developer.wordpress.org/resource/dashicons/#info */
    public const INFO_2 = self::DASHICON_PREFIX . 'info';
    /** @var string https://developer.wordpress.org/resource/dashicons/#cart */
    public const CART = self::DASHICON_PREFIX . 'cart';
    /** @var string https://developer.wordpress.org/resource/dashicons/#feedback */
    public const FEEDBACK = self::DASHICON_PREFIX . 'feedback';
    /** @var string https://developer.wordpress.org/resource/dashicons/#cloud */
    public const CLOUD = self::DASHICON_PREFIX . 'cloud';
    /** @var string https://developer.wordpress.org/resource/dashicons/#translation */
    public const TRANSLATION = self::DASHICON_PREFIX . 'translation';
    /** @var string https://developer.wordpress.org/resource/dashicons/#tag */
    public const TAG = self::DASHICON_PREFIX . 'tag';
    /** @var string https://developer.wordpress.org/resource/dashicons/#category */
    public const FOLDER_1 = self::DASHICON_PREFIX . 'category';
    /** @var string https://developer.wordpress.org/resource/dashicons/#archive */
    public const ARCHIVE = self::DASHICON_PREFIX . 'archive';
    /** @var string https://developer.wordpress.org/resource/dashicons/#tagcloud */
    public const BLOCKS_2 = self::DASHICON_PREFIX . 'tagcloud';
    /** @var string https://developer.wordpress.org/resource/dashicons/#bell */
    public const NOTIFICATION_BELL = self::DASHICON_PREFIX . 'bell';
    /** @var string https://developer.wordpress.org/resource/dashicons/#yes */
    public const CHECKED_2 = self::DASHICON_PREFIX . 'yes';
    /** @var string https://developer.wordpress.org/resource/dashicons/#yes-alt */
    public const CHECKED_3 = self::DASHICON_PREFIX . 'yes-alt';
    /** @var string https://developer.wordpress.org/resource/dashicons/#no */
    public const X_MARK_1 = self::DASHICON_PREFIX . 'no';
    /** @var string https://developer.wordpress.org/resource/dashicons/#no-alt */
    public const X_MARK_2 = self::DASHICON_PREFIX . 'no-alt';
    /** @var string https://developer.wordpress.org/resource/dashicons/#plus */
    public const ADD_2 = self::DASHICON_PREFIX . 'plus';
    /** @var string https://developer.wordpress.org/resource/dashicons/#plus-alt */
    public const ADD_3 = self::DASHICON_PREFIX . 'plus-alt';
    /** @var string https://developer.wordpress.org/resource/dashicons/#plus-alt2 */
    public const ADD_4 = self::DASHICON_PREFIX . 'plus-alt2';
    /** @var string https://developer.wordpress.org/resource/dashicons/#minus */
    public const REMOVE_2 = self::DASHICON_PREFIX . 'minus';
    /** @var string https://developer.wordpress.org/resource/dashicons/#dismiss */
    public const X_MARK_3 = self::DASHICON_PREFIX . 'dismiss';
    /** @var string https://developer.wordpress.org/resource/dashicons/#marker */
    public const CIRCLE = self::DASHICON_PREFIX . 'marker';
    /** @var string https://developer.wordpress.org/resource/dashicons/#star-filled */
    public const STAR_FULL_FILLED = self::DASHICON_PREFIX . 'star-filled';
    /** @var string https://developer.wordpress.org/resource/dashicons/#star-half */
    public const STAR_HALF_FILLED = self::DASHICON_PREFIX . 'star-half';
    /** @var string https://developer.wordpress.org/resource/dashicons/#star-empty */
    public const STAR_EMPTY = self::DASHICON_PREFIX . 'star-empty';
    /** @var string https://developer.wordpress.org/resource/dashicons/#flag */
    public const FLAG = self::DASHICON_PREFIX . 'flag';
    /** @var string https://developer.wordpress.org/resource/dashicons/#warning */
    public const WARNING = self::DASHICON_PREFIX . 'warning';
    /** @var string https://developer.wordpress.org/resource/dashicons/#location */
    public const LOCATION_1 = self::DASHICON_PREFIX . 'location';
    /** @var string https://developer.wordpress.org/resource/dashicons/#location-alt */
    public const LOCATION_2 = self::DASHICON_PREFIX . 'location-alt';
    /** @var string https://developer.wordpress.org/resource/dashicons/#vault */
    public const VAULT = self::DASHICON_PREFIX . 'vault';
    /** @var string https://developer.wordpress.org/resource/dashicons/#shield */
    public const SHIELD_1 = self::DASHICON_PREFIX . 'shield';
    /** @var string https://developer.wordpress.org/resource/dashicons/#shield-alt */
    public const SHIELD_2 = self::DASHICON_PREFIX . 'shield-alt';
    /** @var string https://developer.wordpress.org/resource/dashicons/#sos */
    public const SOS = self::DASHICON_PREFIX . 'sos';
    /** @var string https://developer.wordpress.org/resource/dashicons/#search */
    public const SEARCH = self::DASHICON_PREFIX . 'search';
    /** @var string https://developer.wordpress.org/resource/dashicons/#slides */
    public const SLIDES = self::DASHICON_PREFIX . 'slides';
    /** @var string https://developer.wordpress.org/resource/dashicons/#text-page */
    public const FILE_TEXT_2 = self::DASHICON_PREFIX . 'text-page';
    /** @var string https://developer.wordpress.org/resource/dashicons/#analytics */
    public const REPORT = self::DASHICON_PREFIX . 'analytics';
    /** @var string https://developer.wordpress.org/resource/dashicons/#chart-pie */
    public const CHART_PIE = self::DASHICON_PREFIX . 'chart-pie';
    /** @var string https://developer.wordpress.org/resource/dashicons/#chart-bar */
    public const CHART_BAR = self::DASHICON_PREFIX . 'chart-bar';
    /** @var string https://developer.wordpress.org/resource/dashicons/#chart-line */
    public const CHART_LINE = self::DASHICON_PREFIX . 'chart-line';
    /** @var string https://developer.wordpress.org/resource/dashicons/#chart-area */
    public const CHART_AREA = self::DASHICON_PREFIX . 'chart-area';
    /** @var string https://developer.wordpress.org/resource/dashicons/#groups */
    public const USERS = self::DASHICON_PREFIX . 'groups';
    /** @var string https://developer.wordpress.org/resource/dashicons/#businessman */
    public const USER_2 = self::DASHICON_PREFIX . 'businessman';
    /** @var string https://developer.wordpress.org/resource/dashicons/#businesswoman */
    public const USER_3 = self::DASHICON_PREFIX . 'businesswoman';
    /** @var string https://developer.wordpress.org/resource/dashicons/#businessperson */
    public const USER_4 = self::DASHICON_PREFIX . 'businessperson';
    /** @var string https://developer.wordpress.org/resource/dashicons/#id */
    public const ID_CARD_1 = self::DASHICON_PREFIX . 'id';
    /** @var string https://developer.wordpress.org/resource/dashicons/#id-alt */
    public const ID_CARD_2 = self::DASHICON_PREFIX . 'id-alt';
    /** @var string https://developer.wordpress.org/resource/dashicons/#products */
    public const BAG = self::DASHICON_PREFIX . 'products';
    /** @var string https://developer.wordpress.org/resource/dashicons/#awards */
    public const BADGE = self::DASHICON_PREFIX . 'awards';
    /** @var string https://developer.wordpress.org/resource/dashicons/#forms */
    public const CHECKBOXES = self::DASHICON_PREFIX . 'forms';
    /** @var string https://developer.wordpress.org/resource/dashicons/#testimonial */
    public const COMMENT_2 = self::DASHICON_PREFIX . 'testimonial';
    /** @var string https://developer.wordpress.org/resource/dashicons/#portfolio */
    public const OPENED_FOLDER_1 = self::DASHICON_PREFIX . 'portfolio';
    /** @var string https://developer.wordpress.org/resource/dashicons/#book */
    public const BOOK_1 = self::DASHICON_PREFIX . 'book';
    /** @var string https://developer.wordpress.org/resource/dashicons/#book-alt */
    public const BOOK_2 = self::DASHICON_PREFIX . 'book-alt';
    /** @var string https://developer.wordpress.org/resource/dashicons/#download */
    public const DOWNLOAD = self::DASHICON_PREFIX . 'download';
    /** @var string https://developer.wordpress.org/resource/dashicons/#upload */
    public const UPLOAD = self::DASHICON_PREFIX . 'upload';
    /** @var string https://developer.wordpress.org/resource/dashicons/#backup */
    public const BACKUP = self::DASHICON_PREFIX . 'backup';
    /** @var string https://developer.wordpress.org/resource/dashicons/#clock */
    public const CLOCK = self::DASHICON_PREFIX . 'clock';
    /** @var string https://developer.wordpress.org/resource/dashicons/#lightbulb */
    public const LIGHT_BULB = self::DASHICON_PREFIX . 'lightbulb';
    /** @var string https://developer.wordpress.org/resource/dashicons/#microphone */
    public const MICROPHONE = self::DASHICON_PREFIX . 'microphone';
    /** @var string https://developer.wordpress.org/resource/dashicons/#desktop */
    public const SCREEN_DESKTOP = self::DASHICON_PREFIX . 'desktop';
    /** @var string https://developer.wordpress.org/resource/dashicons/#tablet */
    public const SCREEN_TABLET = self::DASHICON_PREFIX . 'tablet';
    /** @var string https://developer.wordpress.org/resource/dashicons/#phone */
    public const SCREEN_PHONE = self::DASHICON_PREFIX . 'phone';
    /** @var string https://developer.wordpress.org/resource/dashicons/#carrot */
    public const CARROT = self::DASHICON_PREFIX . 'carrot';
    /** @var string https://developer.wordpress.org/resource/dashicons/#building */
    public const BUILDING = self::DASHICON_PREFIX . 'building';
    /** @var string https://developer.wordpress.org/resource/dashicons/#store */
    public const STORE = self::DASHICON_PREFIX . 'store';
    /** @var string https://developer.wordpress.org/resource/dashicons/#album */
    public const ALBUM = self::DASHICON_PREFIX . 'album';
    /** @var string https://developer.wordpress.org/resource/dashicons/#palmtree */
    public const TREE = self::DASHICON_PREFIX . 'palmtree';
    /** @var string https://developer.wordpress.org/resource/dashicons/#tickets-alt */
    public const TICKETS = self::DASHICON_PREFIX . 'tickets-alt';
    /** @var string https://developer.wordpress.org/resource/dashicons/#money */
    public const MONEY_1 = self::DASHICON_PREFIX . 'money';
    /** @var string https://developer.wordpress.org/resource/dashicons/#money-alt */
    public const MONEY_2 = self::DASHICON_PREFIX . 'money-alt';
    /** @var string https://developer.wordpress.org/resource/dashicons/#smiley */
    public const SMILEY = self::DASHICON_PREFIX . 'smiley';
    /** @var string https://developer.wordpress.org/resource/dashicons/#thumbs-up */
    public const LIKE = self::DASHICON_PREFIX . 'thumbs-up';
    /** @var string https://developer.wordpress.org/resource/dashicons/#thumbs-down */
    public const DISLIKE = self::DASHICON_PREFIX . 'thumbs-down';
    /** @var string https://developer.wordpress.org/resource/dashicons/#layout */
    public const LAYOUT = self::DASHICON_PREFIX . 'layout';
    /** @var string https://developer.wordpress.org/resource/dashicons/#paperclip */
    public const PAPERCLIP = self::DASHICON_PREFIX . 'paperclip';
    /** @var string https://developer.wordpress.org/resource/dashicons/#color-picker */
    public const DROPPER = self::DASHICON_PREFIX . 'color-picker';
    /** @var string https://developer.wordpress.org/resource/dashicons/#edit-large */
    public const PENCIL_2 = self::DASHICON_PREFIX . 'edit-large';
    /** @var string https://developer.wordpress.org/resource/dashicons/#edit-page */
    public const EDIT = self::DASHICON_PREFIX . 'edit-page';
    /** @var string https://developer.wordpress.org/resource/dashicons/#airplane */
    public const AIRPLANE = self::DASHICON_PREFIX . 'airplane';
    /** @var string https://developer.wordpress.org/resource/dashicons/#bank */
    public const BANK = self::DASHICON_PREFIX . 'bank';
    /** @var string https://developer.wordpress.org/resource/dashicons/#beer */
    public const BEER = self::DASHICON_PREFIX . 'beer';
    /** @var string https://developer.wordpress.org/resource/dashicons/#calculator */
    public const CALCULATOR = self::DASHICON_PREFIX . 'calculator';
    /** @var string https://developer.wordpress.org/resource/dashicons/#car */
    public const CAR = self::DASHICON_PREFIX . 'car';
    /** @var string https://developer.wordpress.org/resource/dashicons/#coffee */
    public const MUG = self::DASHICON_PREFIX . 'coffee';
    /** @var string https://developer.wordpress.org/resource/dashicons/#drumstick */
    public const FOOD_1 = self::DASHICON_PREFIX . 'drumstick';
    /** @var string https://developer.wordpress.org/resource/dashicons/#food */
    public const FOOD_2 = self::DASHICON_PREFIX . 'food';
    /** @var string https://developer.wordpress.org/resource/dashicons/#fullscreen-alt */
    public const FULLSCREEN = self::DASHICON_PREFIX . 'fullscreen-alt';
    /** @var string https://developer.wordpress.org/resource/dashicons/#fullscreen-exit-alt */
    public const FULLSCREEN_EXIT = self::DASHICON_PREFIX . 'fullscreen-exit-alt';
    /** @var string https://developer.wordpress.org/resource/dashicons/#games */
    public const JOYSTICK = self::DASHICON_PREFIX . 'games';
    /** @var string https://developer.wordpress.org/resource/dashicons/#hourglass */
    public const HOURGLASS = self::DASHICON_PREFIX . 'hourglass';
    /** @var string https://developer.wordpress.org/resource/dashicons/#open-folder */
    public const OPENED_FOLDER_2 = self::DASHICON_PREFIX . 'open-folder';
    /** @var string https://developer.wordpress.org/resource/dashicons/#pdf */
    public const FILE_PDF = self::DASHICON_PREFIX . 'pdf';
    /** @var string https://developer.wordpress.org/resource/dashicons/#pets */
    public const PETS = self::DASHICON_PREFIX . 'pets';
    /** @var string https://developer.wordpress.org/resource/dashicons/#printer */
    public const PRINTER = self::DASHICON_PREFIX . 'printer';
    /** @var string https://developer.wordpress.org/resource/dashicons/#privacy */
    public const PRIVACY = self::DASHICON_PREFIX . 'privacy';
    /** @var string https://developer.wordpress.org/resource/dashicons/#superhero */
    public const SUPERHERO_1 = self::DASHICON_PREFIX . 'superhero';
    /** @var string https://developer.wordpress.org/resource/dashicons/#superhero-alt */
    public const SUPERHERO_2 = self::DASHICON_PREFIX . 'superhero-alt';
}
