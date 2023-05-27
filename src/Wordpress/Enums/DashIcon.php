<?php

namespace Wordless\Wordpress\Enums;

enum DashIcon: string
{
    private const DASHICON_PREFIX = 'dashicons-';

    /** @var string https://developer.wordpress.org/resource/dashicons/#menu */
    case menu_1 = self::DASHICON_PREFIX . 'menu';
    /** @var string https://developer.wordpress.org/resource/dashicons/#menu-alt */
    case menu_2 = self::DASHICON_PREFIX . 'menu-alt';
    /** @var string https://developer.wordpress.org/resource/dashicons/#menu-alt2 */
    case menu_3 = self::DASHICON_PREFIX . 'menu-alt2';
    /** @var string https://developer.wordpress.org/resource/dashicons/#menu-alt3 */
    case menu_4 = self::DASHICON_PREFIX . 'menu-alt3';
    /** @var string https://developer.wordpress.org/resource/dashicons/#admin-site */
    case world_america = self::DASHICON_PREFIX . 'admin-site';
    /** @var string https://developer.wordpress.org/resource/dashicons/#admin-site-alt */
    case world_europe_and_africa = self::DASHICON_PREFIX . 'admin-site-alt';
    /** @var string https://developer.wordpress.org/resource/dashicons/#admin-site-alt2 */
    case world_east = self::DASHICON_PREFIX . 'admin-site-alt2';
    /** @var string https://developer.wordpress.org/resource/dashicons/#admin-site-alt3 */
    case world_general = self::DASHICON_PREFIX . 'admin-site-alt3';
    /** @var string https://developer.wordpress.org/resource/dashicons/#dashboard */
    case dashboard_1 = self::DASHICON_PREFIX . 'dashboard';
    /** @var string https://developer.wordpress.org/resource/dashicons/#admin-post */
    case pin_1 = self::DASHICON_PREFIX . 'admin-post';
    /** @var string https://developer.wordpress.org/resource/dashicons/#admin-media */
    case media = self::DASHICON_PREFIX . 'admin-media';
    /** @var string https://developer.wordpress.org/resource/dashicons/#admin-links */
    case hyperlink = self::DASHICON_PREFIX . 'admin-links';
    /** @var string https://developer.wordpress.org/resource/dashicons/#admin-page */
    case copy = self::DASHICON_PREFIX . 'admin-page';
    /** @var string https://developer.wordpress.org/resource/dashicons/#admin-comments */
    case comment_1 = self::DASHICON_PREFIX . 'admin-comments';
    /** @var string https://developer.wordpress.org/resource/dashicons/#admin-appearance */
    case brush = self::DASHICON_PREFIX . 'admin-appearance';
    /** @var string https://developer.wordpress.org/resource/dashicons/#admin-plugins */
    case plug = self::DASHICON_PREFIX . 'admin-plugins';
    /** @var string https://developer.wordpress.org/resource/dashicons/#admin-users */
    case user_1 = self::DASHICON_PREFIX . 'admin-users';
    /** @var string https://developer.wordpress.org/resource/dashicons/#admin-tools */
    case tool = self::DASHICON_PREFIX . 'admin-tools';
    /** @var string https://developer.wordpress.org/resource/dashicons/#admin-settings */
    case setting = self::DASHICON_PREFIX . 'admin-settings';
    /** @var string https://developer.wordpress.org/resource/dashicons/#admin-network */
    case key_1 = self::DASHICON_PREFIX . 'admin-network';
    /** @var string https://developer.wordpress.org/resource/dashicons/#admin-home */
    case home = self::DASHICON_PREFIX . 'admin-home';
    /** @var string https://developer.wordpress.org/resource/dashicons/#admin-generic */
    case gear = self::DASHICON_PREFIX . 'admin-generic';
    /** @var string https://developer.wordpress.org/resource/dashicons/#admin-collapse */
    case circle_arrow_left = self::DASHICON_PREFIX . 'admin-collapse';
    /** @var string https://developer.wordpress.org/resource/dashicons/#filter */
    case filter = self::DASHICON_PREFIX . 'filter';
    /** @var string https://developer.wordpress.org/resource/dashicons/#admin-customizer */
    case paintbrush = self::DASHICON_PREFIX . 'admin-customizer';
    /** @var string https://developer.wordpress.org/resource/dashicons/#admin-multisite */
    case houses = self::DASHICON_PREFIX . 'admin-multisite';
    /** @var string https://developer.wordpress.org/resource/dashicons/#welcome-write-blog */
    case write = self::DASHICON_PREFIX . 'welcome-write-blog';
    /** @var string https://developer.wordpress.org/resource/dashicons/#welcome-add-page */
    case add_page = self::DASHICON_PREFIX . 'welcome-add-page';
    /** @var string https://developer.wordpress.org/resource/dashicons/#welcome-view-site */
    case screen_visibility = self::DASHICON_PREFIX . 'welcome-view-site';
    /** @var string https://developer.wordpress.org/resource/dashicons/#welcome-widgets-menus */
    case dashboard_2 = self::DASHICON_PREFIX . 'welcome-widgets-menus';
    /** @var string https://developer.wordpress.org/resource/dashicons/#welcome-comments */
    case comment_blocked = self::DASHICON_PREFIX . 'welcome-comments';
    /** @var string https://developer.wordpress.org/resource/dashicons/#welcome-learn-more */
    case graduation_cap = self::DASHICON_PREFIX . 'welcome-learn-more';
    /** @var string https://developer.wordpress.org/resource/dashicons/#format-aside */
    case page = self::DASHICON_PREFIX . 'format-aside';
    /** @var string https://developer.wordpress.org/resource/dashicons/#format-image */
    case image = self::DASHICON_PREFIX . 'format-image';
    /** @var string https://developer.wordpress.org/resource/dashicons/#format-gallery */
    case gallery = self::DASHICON_PREFIX . 'format-gallery';
    /** @var string https://developer.wordpress.org/resource/dashicons/#format-video */
    case video_1 = self::DASHICON_PREFIX . 'format-video';
    /** @var string https://developer.wordpress.org/resource/dashicons/#format-status */
    case comment_dots = self::DASHICON_PREFIX . 'format-status';
    /** @var string https://developer.wordpress.org/resource/dashicons/#format-quote */
    case quote_1 = self::DASHICON_PREFIX . 'format-quote';
    /** @var string https://developer.wordpress.org/resource/dashicons/#format-chat */
    case chat = self::DASHICON_PREFIX . 'format-chat';
    /** @var string https://developer.wordpress.org/resource/dashicons/#format-audio */
    case audio = self::DASHICON_PREFIX . 'format-audio';
    /** @var string https://developer.wordpress.org/resource/dashicons/#camera */
    case camera_1 = self::DASHICON_PREFIX . 'camera';
    /** @var string https://developer.wordpress.org/resource/dashicons/#camera-alt */
    case camera_2 = self::DASHICON_PREFIX . 'camera-alt';
    /** @var string https://developer.wordpress.org/resource/dashicons/#images-alt */
    case images_1 = self::DASHICON_PREFIX . 'images-alt';
    /** @var string https://developer.wordpress.org/resource/dashicons/#images-alt2 */
    case images_2 = self::DASHICON_PREFIX . 'images-alt2';
    /** @var string https://developer.wordpress.org/resource/dashicons/#video-alt */
    case camera_3 = self::DASHICON_PREFIX . 'video-alt';
    /** @var string https://developer.wordpress.org/resource/dashicons/#video-alt2 */
    case camera_4 = self::DASHICON_PREFIX . 'video-alt2';
    /** @var string https://developer.wordpress.org/resource/dashicons/#video-alt3 */
    case play_button = self::DASHICON_PREFIX . 'video-alt3';
    /** @var string https://developer.wordpress.org/resource/dashicons/#media-archive */
    case file_compressed = self::DASHICON_PREFIX . 'media-archive';
    /** @var string https://developer.wordpress.org/resource/dashicons/#media-audio */
    case file_audio = self::DASHICON_PREFIX . 'media-audio';
    /** @var string https://developer.wordpress.org/resource/dashicons/#media-code */
    case file_code = self::DASHICON_PREFIX . 'media-code';
    /** @var string https://developer.wordpress.org/resource/dashicons/#media-default */
    case file = self::DASHICON_PREFIX . 'media-default';
    /** @var string https://developer.wordpress.org/resource/dashicons/#media-document */
    case file_document = self::DASHICON_PREFIX . 'media-document';
    /** @var string https://developer.wordpress.org/resource/dashicons/#media-interactive */
    case file_certification = self::DASHICON_PREFIX . 'media-interactive';
    /** @var string https://developer.wordpress.org/resource/dashicons/#media-spreadsheet */
    case file_spreadsheet = self::DASHICON_PREFIX . 'media-spreadsheet';
    /** @var string https://developer.wordpress.org/resource/dashicons/#media-text */
    case file_text_1 = self::DASHICON_PREFIX . 'media-text';
    /** @var string https://developer.wordpress.org/resource/dashicons/#media-video */
    case file_video = self::DASHICON_PREFIX . 'media-video';
    /** @var string https://developer.wordpress.org/resource/dashicons/#playlist-audio */
    case playlist_audio = self::DASHICON_PREFIX . 'playlist-audio';
    /** @var string https://developer.wordpress.org/resource/dashicons/#playlist-video */
    case playlist_video = self::DASHICON_PREFIX . 'playlist-video';
    /** @var string https://developer.wordpress.org/resource/dashicons/#controls-play */
    case play = self::DASHICON_PREFIX . 'controls-play';
    /** @var string https://developer.wordpress.org/resource/dashicons/#controls-pause */
    case pause = self::DASHICON_PREFIX . 'controls-pause';
    /** @var string https://developer.wordpress.org/resource/dashicons/#controls-forward */
    case forward = self::DASHICON_PREFIX . 'controls-forward';
    /** @var string https://developer.wordpress.org/resource/dashicons/#controls-skipforward */
    case skip_forward = self::DASHICON_PREFIX . 'controls-skipforward';
    /** @var string https://developer.wordpress.org/resource/dashicons/#controls-back */
    case back = self::DASHICON_PREFIX . 'controls-back';
    /** @var string https://developer.wordpress.org/resource/dashicons/#controls-skipback */
    case skip_back = self::DASHICON_PREFIX . 'controls-skipback';
    /** @var string https://developer.wordpress.org/resource/dashicons/#controls-repeat */
    case repeat = self::DASHICON_PREFIX . 'controls-repeat';
    /** @var string https://developer.wordpress.org/resource/dashicons/#controls-volumeon */
    case volume_on = self::DASHICON_PREFIX . 'controls-volumeon';
    /** @var string https://developer.wordpress.org/resource/dashicons/#controls-volumeoff */
    case volume_off = self::DASHICON_PREFIX . 'controls-volumeoff';
    /** @var string https://developer.wordpress.org/resource/dashicons/#image-crop */
    case crop_image = self::DASHICON_PREFIX . 'image-crop';
    /** @var string https://developer.wordpress.org/resource/dashicons/#image-rotate */
    case refresh = self::DASHICON_PREFIX . 'image-rotate';
    /** @var string https://developer.wordpress.org/resource/dashicons/#image-rotate-left */
    case rotate_left = self::DASHICON_PREFIX . 'image-rotate-left';
    /** @var string https://developer.wordpress.org/resource/dashicons/#image-rotate-right */
    case rotate_right = self::DASHICON_PREFIX . 'image-rotate-right';
    /** @var string https://developer.wordpress.org/resource/dashicons/#image-flip-vertical */
    case flip_vertical = self::DASHICON_PREFIX . 'image-flip-vertical';
    /** @var string https://developer.wordpress.org/resource/dashicons/#image-flip-horizontal */
    case flip_horizontal = self::DASHICON_PREFIX . 'image-flip-horizontal';
    /** @var string https://developer.wordpress.org/resource/dashicons/#image-filter */
    case circles_triangle = self::DASHICON_PREFIX . 'image-filter';
    /** @var string https://developer.wordpress.org/resource/dashicons/#undo */
    case undo = self::DASHICON_PREFIX . 'undo';
    /** @var string https://developer.wordpress.org/resource/dashicons/#redo */
    case redo = self::DASHICON_PREFIX . 'redo';
    /** @var string https://developer.wordpress.org/resource/dashicons/#database-add */
    case database_add = self::DASHICON_PREFIX . 'database-add';
    /** @var string https://developer.wordpress.org/resource/dashicons/#database */
    case database = self::DASHICON_PREFIX . 'database';
    /** @var string https://developer.wordpress.org/resource/dashicons/#database-export */
    case database_export = self::DASHICON_PREFIX . 'database-export';
    /** @var string https://developer.wordpress.org/resource/dashicons/#database-import */
    case database_import = self::DASHICON_PREFIX . 'database-import';
    /** @var string https://developer.wordpress.org/resource/dashicons/#database-remove */
    case database_remove = self::DASHICON_PREFIX . 'database-remove';
    /** @var string https://developer.wordpress.org/resource/dashicons/#database-view */
    case database_checked = self::DASHICON_PREFIX . 'database-view';
    /** @var string https://developer.wordpress.org/resource/dashicons/#align-full-width */
    case align_top = self::DASHICON_PREFIX . 'align-full-width';
    /** @var string https://developer.wordpress.org/resource/dashicons/#align-pull-left */
    case align_left = self::DASHICON_PREFIX . 'align-pull-left';
    /** @var string https://developer.wordpress.org/resource/dashicons/#align-pull-right */
    case align_right = self::DASHICON_PREFIX . 'align-pull-right';
    /** @var string https://developer.wordpress.org/resource/dashicons/#align-wide */
    case align_center_vertical = self::DASHICON_PREFIX . 'align-wide';
    /** @var string https://developer.wordpress.org/resource/dashicons/#block-default */
    case toy_block = self::DASHICON_PREFIX . 'block-default';
    /** @var string https://developer.wordpress.org/resource/dashicons/#button */
    case keyboard_button = self::DASHICON_PREFIX . 'button';
    /** @var string https://developer.wordpress.org/resource/dashicons/#cloud-saved */
    case cloud_checked = self::DASHICON_PREFIX . 'cloud-saved';
    /** @var string https://developer.wordpress.org/resource/dashicons/#cloud-upload */
    case cloud_upload = self::DASHICON_PREFIX . 'cloud-upload';
    /** @var string https://developer.wordpress.org/resource/dashicons/#columns */
    case columns = self::DASHICON_PREFIX . 'columns';
    /** @var string https://developer.wordpress.org/resource/dashicons/#cover-image */
    case align_top_image = self::DASHICON_PREFIX . 'cover-image';
    /** @var string https://developer.wordpress.org/resource/dashicons/#ellipsis */
    case ellipsis = self::DASHICON_PREFIX . 'ellipsis';
    /** @var string https://developer.wordpress.org/resource/dashicons/#embed-audio */
    case embed_audio = self::DASHICON_PREFIX . 'embed-audio';
    /** @var string https://developer.wordpress.org/resource/dashicons/#embed-generic */
    case embed = self::DASHICON_PREFIX . 'embed-generic';
    /** @var string https://developer.wordpress.org/resource/dashicons/#embed-photo */
    case embed_image = self::DASHICON_PREFIX . 'embed-photo';
    /** @var string https://developer.wordpress.org/resource/dashicons/#embed-post */
    case embed_localization = self::DASHICON_PREFIX . 'embed-post';
    /** @var string https://developer.wordpress.org/resource/dashicons/#embed-video */
    case embed_video = self::DASHICON_PREFIX . 'embed-video';
    /** @var string https://developer.wordpress.org/resource/dashicons/#exit */
    case exit = self::DASHICON_PREFIX . 'exit';
    /** @var string https://developer.wordpress.org/resource/dashicons/#heading */
    case heading = self::DASHICON_PREFIX . 'heading';
    /** @var string https://developer.wordpress.org/resource/dashicons/#html */
    case html = self::DASHICON_PREFIX . 'html';
    /** @var string https://developer.wordpress.org/resource/dashicons/#info-outline */
    case info_1 = self::DASHICON_PREFIX . 'info-outline';
    /** @var string https://developer.wordpress.org/resource/dashicons/#insert */
    case add_1 = self::DASHICON_PREFIX . 'insert';
    /** @var string https://developer.wordpress.org/resource/dashicons/#insert-after */
    case add_to_top = self::DASHICON_PREFIX . 'insert-after';
    /** @var string https://developer.wordpress.org/resource/dashicons/#insert-before */
    case add_to_bottom = self::DASHICON_PREFIX . 'insert-before';
    /** @var string https://developer.wordpress.org/resource/dashicons/#remove */
    case remove_1 = self::DASHICON_PREFIX . 'remove';
    /** @var string https://developer.wordpress.org/resource/dashicons/#saved */
    case checked_1 = self::DASHICON_PREFIX . 'saved';
    /** @var string https://developer.wordpress.org/resource/dashicons/#shortcode */
    case shortcode = self::DASHICON_PREFIX . 'shortcode';
    /** @var string https://developer.wordpress.org/resource/dashicons/#table-col-after */
    case table_add_column_right = self::DASHICON_PREFIX . 'table-col-after';
    /** @var string https://developer.wordpress.org/resource/dashicons/#table-col-before */
    case table_add_column_left = self::DASHICON_PREFIX . 'table-col-before';
    /** @var string https://developer.wordpress.org/resource/dashicons/#table-col-delete */
    case table_remove_column = self::DASHICON_PREFIX . 'table-col-delete';
    /** @var string https://developer.wordpress.org/resource/dashicons/#table-row-after */
    case table_add_row_to_bottom = self::DASHICON_PREFIX . 'table-row-after';
    /** @var string https://developer.wordpress.org/resource/dashicons/#table-row-before */
    case table_add_row_to_top = self::DASHICON_PREFIX . 'table-row-before';
    /** @var string https://developer.wordpress.org/resource/dashicons/#table-row-delete */
    case table_remove_row = self::DASHICON_PREFIX . 'table-row-delete';
    /** @var string https://developer.wordpress.org/resource/dashicons/#editor-bold */
    case bold = self::DASHICON_PREFIX . 'editor-bold';
    /** @var string https://developer.wordpress.org/resource/dashicons/#editor-italic */
    case italic = self::DASHICON_PREFIX . 'editor-italic';
    /** @var string https://developer.wordpress.org/resource/dashicons/#editor-ul */
    case list_unordered = self::DASHICON_PREFIX . 'editor-ul';
    /** @var string https://developer.wordpress.org/resource/dashicons/#editor-ol */
    case list_ordered = self::DASHICON_PREFIX . 'editor-ol';
    /** @var string https://developer.wordpress.org/resource/dashicons/#editor-ol-rtl */
    case list_ordered_rtl = self::DASHICON_PREFIX . 'editor-ol-rtl';
    /** @var string https://developer.wordpress.org/resource/dashicons/#editor-quote */
    case quote_2 = self::DASHICON_PREFIX . 'editor-quote';
    /** @var string https://developer.wordpress.org/resource/dashicons/#editor-alignleft */
    case text_align_left = self::DASHICON_PREFIX . 'editor-alignleft';
    /** @var string https://developer.wordpress.org/resource/dashicons/#editor-aligncenter */
    case text_align_center = self::DASHICON_PREFIX . 'editor-aligncenter';
    /** @var string https://developer.wordpress.org/resource/dashicons/#editor-alignright */
    case text_align_right = self::DASHICON_PREFIX . 'editor-alignright';
    /** @var string https://developer.wordpress.org/resource/dashicons/#editor-insertmore */
    case text_linebreak = self::DASHICON_PREFIX . 'editor-insertmore';
    /** @var string https://developer.wordpress.org/resource/dashicons/#editor-spellcheck */
    case spellcheck = self::DASHICON_PREFIX . 'editor-spellcheck';
    /** @var string https://developer.wordpress.org/resource/dashicons/#editor-expand */
    case expand = self::DASHICON_PREFIX . 'editor-expand';
    /** @var string https://developer.wordpress.org/resource/dashicons/#editor-contract */
    case retract = self::DASHICON_PREFIX . 'editor-contract';
    /** @var string https://developer.wordpress.org/resource/dashicons/#editor-kitchensink */
    case blocks_wrapped = self::DASHICON_PREFIX . 'editor-kitchensink';
    /** @var string https://developer.wordpress.org/resource/dashicons/#editor-underline */
    case underline = self::DASHICON_PREFIX . 'editor-underline';
    /** @var string https://developer.wordpress.org/resource/dashicons/#editor-justify */
    case text_align_justify = self::DASHICON_PREFIX . 'editor-justify';
    /** @var string https://developer.wordpress.org/resource/dashicons/#editor-textcolor */
    case fontcolor = self::DASHICON_PREFIX . 'editor-textcolor';
    /** @var string https://developer.wordpress.org/resource/dashicons/#editor-paste-word */
    case paste_word = self::DASHICON_PREFIX . 'editor-paste-word';
    /** @var string https://developer.wordpress.org/resource/dashicons/#editor-paste-text */
    case paste_text = self::DASHICON_PREFIX . 'editor-paste-text';
    /** @var string https://developer.wordpress.org/resource/dashicons/#editor-removeformatting */
    case eraser = self::DASHICON_PREFIX . 'editor-removeformatting';
    /** @var string https://developer.wordpress.org/resource/dashicons/#editor-video */
    case video_2 = self::DASHICON_PREFIX . 'editor-video';
    /** @var string https://developer.wordpress.org/resource/dashicons/#editor-customchar */
    case omega = self::DASHICON_PREFIX . 'editor-customchar';
    /** @var string https://developer.wordpress.org/resource/dashicons/#editor-outdent */
    case undo_indent = self::DASHICON_PREFIX . 'editor-outdent';
    /** @var string https://developer.wordpress.org/resource/dashicons/#editor-indent */
    case indent = self::DASHICON_PREFIX . 'editor-indent';
    /** @var string https://developer.wordpress.org/resource/dashicons/#editor-help */
    case help = self::DASHICON_PREFIX . 'editor-help';
    /** @var string https://developer.wordpress.org/resource/dashicons/#editor-strikethrough */
    case strikethrough = self::DASHICON_PREFIX . 'editor-strikethrough';
    /** @var string https://developer.wordpress.org/resource/dashicons/#editor-unlink */
    case unlink = self::DASHICON_PREFIX . 'editor-unlink';
    /** @var string https://developer.wordpress.org/resource/dashicons/#editor-rtl */
    case rtl = self::DASHICON_PREFIX . 'editor-rtl';
    /** @var string https://developer.wordpress.org/resource/dashicons/#editor-ltr */
    case ltr = self::DASHICON_PREFIX . 'editor-ltr';
    /** @var string https://developer.wordpress.org/resource/dashicons/#editor-break */
    case enter = self::DASHICON_PREFIX . 'editor-break';
    /** @var string https://developer.wordpress.org/resource/dashicons/#editor-code */
    case code = self::DASHICON_PREFIX . 'editor-code';
    /** @var string https://developer.wordpress.org/resource/dashicons/#editor-table */
    case text_columns = self::DASHICON_PREFIX . 'editor-table';
    /** @var string https://developer.wordpress.org/resource/dashicons/#align-left */
    case align_left_to_text = self::DASHICON_PREFIX . 'align-left';
    /** @var string https://developer.wordpress.org/resource/dashicons/#align-right */
    case align_right_to_text = self::DASHICON_PREFIX . 'align-right';
    /** @var string https://developer.wordpress.org/resource/dashicons/#align-center */
    case align_block_center_to_text = self::DASHICON_PREFIX . 'align-center';
    /** @var string https://developer.wordpress.org/resource/dashicons/#align-none */
    case align_block_left_to_text = self::DASHICON_PREFIX . 'align-none';
    /** @var string https://developer.wordpress.org/resource/dashicons/#lock */
    case lock = self::DASHICON_PREFIX . 'lock';
    /** @var string https://developer.wordpress.org/resource/dashicons/#unlock */
    case unlock = self::DASHICON_PREFIX . 'unlock';
    /** @var string https://developer.wordpress.org/resource/dashicons/#calendar */
    case calendar_1 = self::DASHICON_PREFIX . 'calendar';
    /** @var string https://developer.wordpress.org/resource/dashicons/#calendar-alt */
    case calendar_2 = self::DASHICON_PREFIX . 'calendar-alt';
    /** @var string https://developer.wordpress.org/resource/dashicons/#visibility */
    case visible = self::DASHICON_PREFIX . 'visibility';
    /** @var string https://developer.wordpress.org/resource/dashicons/#hidden */
    case hidden = self::DASHICON_PREFIX . 'hidden';
    /** @var string https://developer.wordpress.org/resource/dashicons/#post-status */
    case key_2 = self::DASHICON_PREFIX . 'post-status';
    /** @var string https://developer.wordpress.org/resource/dashicons/#edit */
    case pencil_1 = self::DASHICON_PREFIX . 'edit';
    /** @var string https://developer.wordpress.org/resource/dashicons/#trash */
    case trash = self::DASHICON_PREFIX . 'trash';
    /** @var string https://developer.wordpress.org/resource/dashicons/#sticky */
    case pin_2 = self::DASHICON_PREFIX . 'sticky';
    /** @var string https://developer.wordpress.org/resource/dashicons/#external */
    case external_1 = self::DASHICON_PREFIX . 'external';
    /** @var string https://developer.wordpress.org/resource/dashicons/#arrow-up */
    case arrow_up_1 = self::DASHICON_PREFIX . 'arrow-up';
    /** @var string https://developer.wordpress.org/resource/dashicons/#arrow-down */
    case arrow_down_1 = self::DASHICON_PREFIX . 'arrow-down';
    /** @var string https://developer.wordpress.org/resource/dashicons/#arrow-right */
    case arrow_right_1 = self::DASHICON_PREFIX . 'arrow-right';
    /** @var string https://developer.wordpress.org/resource/dashicons/#arrow-left */
    case arrow_left_1 = self::DASHICON_PREFIX . 'arrow-left';
    /** @var string https://developer.wordpress.org/resource/dashicons/#arrow-up-alt */
    case arrow_up_2 = self::DASHICON_PREFIX . 'arrow-up-alt';
    /** @var string https://developer.wordpress.org/resource/dashicons/#arrow-down-alt */
    case arrow_down_2 = self::DASHICON_PREFIX . 'arrow-down-alt';
    /** @var string https://developer.wordpress.org/resource/dashicons/#arrow-right-alt */
    case arrow_right_2 = self::DASHICON_PREFIX . 'arrow-right-alt';
    /** @var string https://developer.wordpress.org/resource/dashicons/#arrow-left-alt */
    case arrow_left_2 = self::DASHICON_PREFIX . 'arrow-left-alt';
    /** @var string https://developer.wordpress.org/resource/dashicons/#arrow-up-alt2 */
    case arrow_up_3 = self::DASHICON_PREFIX . 'arrow-up-alt2';
    /** @var string https://developer.wordpress.org/resource/dashicons/#arrow-down-alt2 */
    case arrow_down_3 = self::DASHICON_PREFIX . 'arrow-down-alt2';
    /** @var string https://developer.wordpress.org/resource/dashicons/#arrow-right-alt2 */
    case arrow_right_3 = self::DASHICON_PREFIX . 'arrow-right-alt2';
    /** @var string https://developer.wordpress.org/resource/dashicons/#arrow-left-alt2 */
    case arrow_left_3 = self::DASHICON_PREFIX . 'arrow-left-alt2';
    /** @var string https://developer.wordpress.org/resource/dashicons/#sort */
    case up_down = self::DASHICON_PREFIX . 'sort';
    /** @var string https://developer.wordpress.org/resource/dashicons/#leftright */
    case left_right = self::DASHICON_PREFIX . 'leftright';
    /** @var string https://developer.wordpress.org/resource/dashicons/#randomize */
    case randomize = self::DASHICON_PREFIX . 'randomize';
    /** @var string https://developer.wordpress.org/resource/dashicons/#list-view */
    case list_view_1 = self::DASHICON_PREFIX . 'list-view';
    /** @var string https://developer.wordpress.org/resource/dashicons/#excerpt-view */
    case list_view_2 = self::DASHICON_PREFIX . 'excerpt-view';
    /** @var string https://developer.wordpress.org/resource/dashicons/#grid-view */
    case grid_view = self::DASHICON_PREFIX . 'grid-view';
    /** @var string https://developer.wordpress.org/resource/dashicons/#move */
    case move = self::DASHICON_PREFIX . 'move';
    /** @var string https://developer.wordpress.org/resource/dashicons/#share */
    case share_1 = self::DASHICON_PREFIX . 'share';
    /** @var string https://developer.wordpress.org/resource/dashicons/#share-alt */
    case trade = self::DASHICON_PREFIX . 'share-alt';
    /** @var string https://developer.wordpress.org/resource/dashicons/#share-alt2 */
    case share_2 = self::DASHICON_PREFIX . 'share-alt2';
    /** @var string https://developer.wordpress.org/resource/dashicons/#rss */
    case rss = self::DASHICON_PREFIX . 'rss';
    /** @var string https://developer.wordpress.org/resource/dashicons/#email */
    case email_1 = self::DASHICON_PREFIX . 'email';
    /** @var string https://developer.wordpress.org/resource/dashicons/#email-alt */
    case email_2 = self::DASHICON_PREFIX . 'email-alt';
    /** @var string https://developer.wordpress.org/resource/dashicons/#email-alt2 */
    case email_3 = self::DASHICON_PREFIX . 'email-alt2';
    /** @var string https://developer.wordpress.org/resource/dashicons/#networking */
    case network_1 = self::DASHICON_PREFIX . 'networking';
    /** @var string https://developer.wordpress.org/resource/dashicons/#amazon */
    case logo_amazon = self::DASHICON_PREFIX . 'amazon';
    /** @var string https://developer.wordpress.org/resource/dashicons/#facebook */
    case logo_facebook_1 = self::DASHICON_PREFIX . 'facebook';
    /** @var string https://developer.wordpress.org/resource/dashicons/#facebook-alt */
    case logo_facebook_2 = self::DASHICON_PREFIX . 'facebook-alt';
    /** @var string https://developer.wordpress.org/resource/dashicons/#google */
    case logo_google = self::DASHICON_PREFIX . 'google';
    /** @var string https://developer.wordpress.org/resource/dashicons/#instagram */
    case logo_instagram = self::DASHICON_PREFIX . 'instagram';
    /** @var string https://developer.wordpress.org/resource/dashicons/#linkedin */
    case logo_linkedin = self::DASHICON_PREFIX . 'linkedin';
    /** @var string https://developer.wordpress.org/resource/dashicons/#pinterest */
    case logo_pinterest = self::DASHICON_PREFIX . 'pinterest';
    /** @var string https://developer.wordpress.org/resource/dashicons/#podio */
    case logo_podio = self::DASHICON_PREFIX . 'podio';
    /** @var string https://developer.wordpress.org/resource/dashicons/#spotify */
    case logo_spotify = self::DASHICON_PREFIX . 'spotify';
    /** @var string https://developer.wordpress.org/resource/dashicons/#twitch */
    case logo_twitch = self::DASHICON_PREFIX . 'twitch';
    /** @var string https://developer.wordpress.org/resource/dashicons/#twitter */
    case logo_twitter_1 = self::DASHICON_PREFIX . 'twitter';
    /** @var string https://developer.wordpress.org/resource/dashicons/#twitter-alt */
    case logo_twitter_2 = self::DASHICON_PREFIX . 'twitter-alt';
    /** @var string https://developer.wordpress.org/resource/dashicons/#whatsapp */
    case logo_whatsapp = self::DASHICON_PREFIX . 'whatsapp';
    /** @var string https://developer.wordpress.org/resource/dashicons/#xing */
    case logo_xing = self::DASHICON_PREFIX . 'xing';
    /** @var string https://developer.wordpress.org/resource/dashicons/#youtube */
    case logo_youtube = self::DASHICON_PREFIX . 'youtube';
    /** @var string https://developer.wordpress.org/resource/dashicons/#hammer */
    case hammer = self::DASHICON_PREFIX . 'hammer';
    /** @var string https://developer.wordpress.org/resource/dashicons/#art */
    case palette = self::DASHICON_PREFIX . 'art';
    /** @var string https://developer.wordpress.org/resource/dashicons/#migrate */
    case external_2 = self::DASHICON_PREFIX . 'migrate';
    /** @var string https://developer.wordpress.org/resource/dashicons/#performance */
    case performance = self::DASHICON_PREFIX . 'performance';
    /** @var string https://developer.wordpress.org/resource/dashicons/#universal-access */
    case accessibility_1 = self::DASHICON_PREFIX . 'universal-access';
    /** @var string https://developer.wordpress.org/resource/dashicons/#universal-access-alt */
    case accessibility_2 = self::DASHICON_PREFIX . 'universal-access-alt';
    /** @var string https://developer.wordpress.org/resource/dashicons/#tickets */
    case wordpress_tickets = self::DASHICON_PREFIX . 'tickets';
    /** @var string https://developer.wordpress.org/resource/dashicons/#nametag */
    case name_tag = self::DASHICON_PREFIX . 'nametag';
    /** @var string https://developer.wordpress.org/resource/dashicons/#clipboard */
    case clipboard = self::DASHICON_PREFIX . 'clipboard';
    /** @var string https://developer.wordpress.org/resource/dashicons/#heart */
    case heart = self::DASHICON_PREFIX . 'heart';
    /** @var string https://developer.wordpress.org/resource/dashicons/#megaphone */
    case megaphone = self::DASHICON_PREFIX . 'megaphone';
    /** @var string https://developer.wordpress.org/resource/dashicons/#schedule */
    case grid = self::DASHICON_PREFIX . 'schedule';
    /** @var string https://developer.wordpress.org/resource/dashicons/#tide */
    case tide = self::DASHICON_PREFIX . 'tide';
    /** @var string https://developer.wordpress.org/resource/dashicons/#rest-api */
    case network_2 = self::DASHICON_PREFIX . 'rest-api';
    /** @var string https://developer.wordpress.org/resource/dashicons/#code-standards */
    case standard_checker = self::DASHICON_PREFIX . 'code-standards';
    /** @var string https://developer.wordpress.org/resource/dashicons/#buddicons-activity */
    case puppy = self::DASHICON_PREFIX . 'buddicons-activity';
    /** @var string https://developer.wordpress.org/resource/dashicons/#buddicons-bbpress-logo */
    case logo_bbpress = self::DASHICON_PREFIX . 'buddicons-bbpress-logo';
    /** @var string https://developer.wordpress.org/resource/dashicons/#buddicons-buddypress-logo */
    case logo_buddypress = self::DASHICON_PREFIX . 'buddicons-buddypress-logo';
    /** @var string https://developer.wordpress.org/resource/dashicons/#buddicons-community */
    case cake = self::DASHICON_PREFIX . 'buddicons-community';
    /** @var string https://developer.wordpress.org/resource/dashicons/#buddicons-forums */
    case hive = self::DASHICON_PREFIX . 'buddicons-forums';
    /** @var string https://developer.wordpress.org/resource/dashicons/#buddicons-friends */
    case candles = self::DASHICON_PREFIX . 'buddicons-friends';
    /** @var string https://developer.wordpress.org/resource/dashicons/#buddicons-groups */
    case balloons = self::DASHICON_PREFIX . 'buddicons-groups';
    /** @var string https://developer.wordpress.org/resource/dashicons/#buddicons-pm */
    case opened_mail = self::DASHICON_PREFIX . 'buddicons-pm';
    /** @var string https://developer.wordpress.org/resource/dashicons/#buddicons-replies */
    case bee = self::DASHICON_PREFIX . 'buddicons-replies';
    /** @var string https://developer.wordpress.org/resource/dashicons/#buddicons-topics */
    case honey_stick = self::DASHICON_PREFIX . 'buddicons-topics';
    /** @var string https://developer.wordpress.org/resource/dashicons/#buddicons-tracking */
    case party_hat = self::DASHICON_PREFIX . 'buddicons-tracking';
    /** @var string https://developer.wordpress.org/resource/dashicons/#wordpress */
    case logo_wordpress_1 = self::DASHICON_PREFIX . 'wordpress';
    /** @var string https://developer.wordpress.org/resource/dashicons/#wordpress-alt */
    case logo_wordpress_2 = self::DASHICON_PREFIX . 'wordpress-alt';
    /** @var string https://developer.wordpress.org/resource/dashicons/#pressthis */
    case file_dropper = self::DASHICON_PREFIX . 'pressthis';
    /** @var string https://developer.wordpress.org/resource/dashicons/#update */
    case update_1 = self::DASHICON_PREFIX . 'update';
    /** @var string https://developer.wordpress.org/resource/dashicons/#update-alt */
    case update_2 = self::DASHICON_PREFIX . 'update-alt';
    /** @var string https://developer.wordpress.org/resource/dashicons/#screenoptions */
    case blocks_1 = self::DASHICON_PREFIX . 'screenoptions';
    /** @var string https://developer.wordpress.org/resource/dashicons/#info */
    case info_2 = self::DASHICON_PREFIX . 'info';
    /** @var string https://developer.wordpress.org/resource/dashicons/#cart */
    case cart = self::DASHICON_PREFIX . 'cart';
    /** @var string https://developer.wordpress.org/resource/dashicons/#feedback */
    case feedback = self::DASHICON_PREFIX . 'feedback';
    /** @var string https://developer.wordpress.org/resource/dashicons/#cloud */
    case cloud = self::DASHICON_PREFIX . 'cloud';
    /** @var string https://developer.wordpress.org/resource/dashicons/#translation */
    case translation = self::DASHICON_PREFIX . 'translation';
    /** @var string https://developer.wordpress.org/resource/dashicons/#tag */
    case tag = self::DASHICON_PREFIX . 'tag';
    /** @var string https://developer.wordpress.org/resource/dashicons/#category */
    case folder_1 = self::DASHICON_PREFIX . 'category';
    /** @var string https://developer.wordpress.org/resource/dashicons/#archive */
    case archive = self::DASHICON_PREFIX . 'archive';
    /** @var string https://developer.wordpress.org/resource/dashicons/#tagcloud */
    case blocks_2 = self::DASHICON_PREFIX . 'tagcloud';
    /** @var string https://developer.wordpress.org/resource/dashicons/#bell */
    case notification_bell = self::DASHICON_PREFIX . 'bell';
    /** @var string https://developer.wordpress.org/resource/dashicons/#yes */
    case checked_2 = self::DASHICON_PREFIX . 'yes';
    /** @var string https://developer.wordpress.org/resource/dashicons/#yes-alt */
    case checked_3 = self::DASHICON_PREFIX . 'yes-alt';
    /** @var string https://developer.wordpress.org/resource/dashicons/#no */
    case x_mark_1 = self::DASHICON_PREFIX . 'no';
    /** @var string https://developer.wordpress.org/resource/dashicons/#no-alt */
    case x_mark_2 = self::DASHICON_PREFIX . 'no-alt';
    /** @var string https://developer.wordpress.org/resource/dashicons/#plus */
    case add_2 = self::DASHICON_PREFIX . 'plus';
    /** @var string https://developer.wordpress.org/resource/dashicons/#plus-alt */
    case add_3 = self::DASHICON_PREFIX . 'plus-alt';
    /** @var string https://developer.wordpress.org/resource/dashicons/#plus-alt2 */
    case add_4 = self::DASHICON_PREFIX . 'plus-alt2';
    /** @var string https://developer.wordpress.org/resource/dashicons/#minus */
    case remove_2 = self::DASHICON_PREFIX . 'minus';
    /** @var string https://developer.wordpress.org/resource/dashicons/#dismiss */
    case x_mark_3 = self::DASHICON_PREFIX . 'dismiss';
    /** @var string https://developer.wordpress.org/resource/dashicons/#marker */
    case circle = self::DASHICON_PREFIX . 'marker';
    /** @var string https://developer.wordpress.org/resource/dashicons/#star-filled */
    case star_full_filled = self::DASHICON_PREFIX . 'star-filled';
    /** @var string https://developer.wordpress.org/resource/dashicons/#star-half */
    case star_half_filled = self::DASHICON_PREFIX . 'star-half';
    /** @var string https://developer.wordpress.org/resource/dashicons/#star-empty */
    case star_empty = self::DASHICON_PREFIX . 'star-empty';
    /** @var string https://developer.wordpress.org/resource/dashicons/#flag */
    case flag = self::DASHICON_PREFIX . 'flag';
    /** @var string https://developer.wordpress.org/resource/dashicons/#warning */
    case warning = self::DASHICON_PREFIX . 'warning';
    /** @var string https://developer.wordpress.org/resource/dashicons/#location */
    case location_1 = self::DASHICON_PREFIX . 'location';
    /** @var string https://developer.wordpress.org/resource/dashicons/#location-alt */
    case location_2 = self::DASHICON_PREFIX . 'location-alt';
    /** @var string https://developer.wordpress.org/resource/dashicons/#vault */
    case vault = self::DASHICON_PREFIX . 'vault';
    /** @var string https://developer.wordpress.org/resource/dashicons/#shield */
    case shield_1 = self::DASHICON_PREFIX . 'shield';
    /** @var string https://developer.wordpress.org/resource/dashicons/#shield-alt */
    case shield_2 = self::DASHICON_PREFIX . 'shield-alt';
    /** @var string https://developer.wordpress.org/resource/dashicons/#sos */
    case sos = self::DASHICON_PREFIX . 'sos';
    /** @var string https://developer.wordpress.org/resource/dashicons/#search */
    case search = self::DASHICON_PREFIX . 'search';
    /** @var string https://developer.wordpress.org/resource/dashicons/#slides */
    case slides = self::DASHICON_PREFIX . 'slides';
    /** @var string https://developer.wordpress.org/resource/dashicons/#text-page */
    case file_text_2 = self::DASHICON_PREFIX . 'text-page';
    /** @var string https://developer.wordpress.org/resource/dashicons/#analytics */
    case report = self::DASHICON_PREFIX . 'analytics';
    /** @var string https://developer.wordpress.org/resource/dashicons/#chart-pie */
    case chart_pie = self::DASHICON_PREFIX . 'chart-pie';
    /** @var string https://developer.wordpress.org/resource/dashicons/#chart-bar */
    case chart_bar = self::DASHICON_PREFIX . 'chart-bar';
    /** @var string https://developer.wordpress.org/resource/dashicons/#chart-line */
    case chart_line = self::DASHICON_PREFIX . 'chart-line';
    /** @var string https://developer.wordpress.org/resource/dashicons/#chart-area */
    case chart_area = self::DASHICON_PREFIX . 'chart-area';
    /** @var string https://developer.wordpress.org/resource/dashicons/#groups */
    case users = self::DASHICON_PREFIX . 'groups';
    /** @var string https://developer.wordpress.org/resource/dashicons/#businessman */
    case user_2 = self::DASHICON_PREFIX . 'businessman';
    /** @var string https://developer.wordpress.org/resource/dashicons/#businesswoman */
    case user_3 = self::DASHICON_PREFIX . 'businesswoman';
    /** @var string https://developer.wordpress.org/resource/dashicons/#businessperson */
    case user_4 = self::DASHICON_PREFIX . 'businessperson';
    /** @var string https://developer.wordpress.org/resource/dashicons/#id */
    case id_card_1 = self::DASHICON_PREFIX . 'id';
    /** @var string https://developer.wordpress.org/resource/dashicons/#id-alt */
    case id_card_2 = self::DASHICON_PREFIX . 'id-alt';
    /** @var string https://developer.wordpress.org/resource/dashicons/#products */
    case bag = self::DASHICON_PREFIX . 'products';
    /** @var string https://developer.wordpress.org/resource/dashicons/#awards */
    case badge = self::DASHICON_PREFIX . 'awards';
    /** @var string https://developer.wordpress.org/resource/dashicons/#forms */
    case checkboxes = self::DASHICON_PREFIX . 'forms';
    /** @var string https://developer.wordpress.org/resource/dashicons/#testimonial */
    case comment_2 = self::DASHICON_PREFIX . 'testimonial';
    /** @var string https://developer.wordpress.org/resource/dashicons/#portfolio */
    case opened_folder_1 = self::DASHICON_PREFIX . 'portfolio';
    /** @var string https://developer.wordpress.org/resource/dashicons/#book */
    case book_1 = self::DASHICON_PREFIX . 'book';
    /** @var string https://developer.wordpress.org/resource/dashicons/#book-alt */
    case book_2 = self::DASHICON_PREFIX . 'book-alt';
    /** @var string https://developer.wordpress.org/resource/dashicons/#download */
    case download = self::DASHICON_PREFIX . 'download';
    /** @var string https://developer.wordpress.org/resource/dashicons/#upload */
    case upload = self::DASHICON_PREFIX . 'upload';
    /** @var string https://developer.wordpress.org/resource/dashicons/#backup */
    case backup = self::DASHICON_PREFIX . 'backup';
    /** @var string https://developer.wordpress.org/resource/dashicons/#clock */
    case clock = self::DASHICON_PREFIX . 'clock';
    /** @var string https://developer.wordpress.org/resource/dashicons/#lightbulb */
    case light_bulb = self::DASHICON_PREFIX . 'lightbulb';
    /** @var string https://developer.wordpress.org/resource/dashicons/#microphone */
    case microphone = self::DASHICON_PREFIX . 'microphone';
    /** @var string https://developer.wordpress.org/resource/dashicons/#desktop */
    case screen_desktop = self::DASHICON_PREFIX . 'desktop';
    /** @var string https://developer.wordpress.org/resource/dashicons/#tablet */
    case screen_tablet = self::DASHICON_PREFIX . 'tablet';
    /** @var string https://developer.wordpress.org/resource/dashicons/#phone */
    case screen_phone = self::DASHICON_PREFIX . 'phone';
    /** @var string https://developer.wordpress.org/resource/dashicons/#carrot */
    case carrot = self::DASHICON_PREFIX . 'carrot';
    /** @var string https://developer.wordpress.org/resource/dashicons/#building */
    case building = self::DASHICON_PREFIX . 'building';
    /** @var string https://developer.wordpress.org/resource/dashicons/#store */
    case store = self::DASHICON_PREFIX . 'store';
    /** @var string https://developer.wordpress.org/resource/dashicons/#album */
    case album = self::DASHICON_PREFIX . 'album';
    /** @var string https://developer.wordpress.org/resource/dashicons/#palmtree */
    case tree = self::DASHICON_PREFIX . 'palmtree';
    /** @var string https://developer.wordpress.org/resource/dashicons/#tickets-alt */
    case tickets = self::DASHICON_PREFIX . 'tickets-alt';
    /** @var string https://developer.wordpress.org/resource/dashicons/#money */
    case money_1 = self::DASHICON_PREFIX . 'money';
    /** @var string https://developer.wordpress.org/resource/dashicons/#money-alt */
    case money_2 = self::DASHICON_PREFIX . 'money-alt';
    /** @var string https://developer.wordpress.org/resource/dashicons/#smiley */
    case smiley = self::DASHICON_PREFIX . 'smiley';
    /** @var string https://developer.wordpress.org/resource/dashicons/#thumbs-up */
    case like = self::DASHICON_PREFIX . 'thumbs-up';
    /** @var string https://developer.wordpress.org/resource/dashicons/#thumbs-down */
    case dislike = self::DASHICON_PREFIX . 'thumbs-down';
    /** @var string https://developer.wordpress.org/resource/dashicons/#layout */
    case layout = self::DASHICON_PREFIX . 'layout';
    /** @var string https://developer.wordpress.org/resource/dashicons/#paperclip */
    case paperclip = self::DASHICON_PREFIX . 'paperclip';
    /** @var string https://developer.wordpress.org/resource/dashicons/#color-picker */
    case dropper = self::DASHICON_PREFIX . 'color-picker';
    /** @var string https://developer.wordpress.org/resource/dashicons/#edit-large */
    case pencil_2 = self::DASHICON_PREFIX . 'edit-large';
    /** @var string https://developer.wordpress.org/resource/dashicons/#edit-page */
    case edit = self::DASHICON_PREFIX . 'edit-page';
    /** @var string https://developer.wordpress.org/resource/dashicons/#airplane */
    case airplane = self::DASHICON_PREFIX . 'airplane';
    /** @var string https://developer.wordpress.org/resource/dashicons/#bank */
    case bank = self::DASHICON_PREFIX . 'bank';
    /** @var string https://developer.wordpress.org/resource/dashicons/#beer */
    case beer = self::DASHICON_PREFIX . 'beer';
    /** @var string https://developer.wordpress.org/resource/dashicons/#calculator */
    case calculator = self::DASHICON_PREFIX . 'calculator';
    /** @var string https://developer.wordpress.org/resource/dashicons/#car */
    case car = self::DASHICON_PREFIX . 'car';
    /** @var string https://developer.wordpress.org/resource/dashicons/#coffee */
    case mug = self::DASHICON_PREFIX . 'coffee';
    /** @var string https://developer.wordpress.org/resource/dashicons/#drumstick */
    case food_1 = self::DASHICON_PREFIX . 'drumstick';
    /** @var string https://developer.wordpress.org/resource/dashicons/#food */
    case food_2 = self::DASHICON_PREFIX . 'food';
    /** @var string https://developer.wordpress.org/resource/dashicons/#fullscreen-alt */
    case fullscreen = self::DASHICON_PREFIX . 'fullscreen-alt';
    /** @var string https://developer.wordpress.org/resource/dashicons/#fullscreen-exit-alt */
    case fullscreen_exit = self::DASHICON_PREFIX . 'fullscreen-exit-alt';
    /** @var string https://developer.wordpress.org/resource/dashicons/#games */
    case joystick = self::DASHICON_PREFIX . 'games';
    /** @var string https://developer.wordpress.org/resource/dashicons/#hourglass */
    case hourglass = self::DASHICON_PREFIX . 'hourglass';
    /** @var string https://developer.wordpress.org/resource/dashicons/#open-folder */
    case opened_folder_2 = self::DASHICON_PREFIX . 'open-folder';
    /** @var string https://developer.wordpress.org/resource/dashicons/#pdf */
    case file_pdf = self::DASHICON_PREFIX . 'pdf';
    /** @var string https://developer.wordpress.org/resource/dashicons/#pets */
    case pets = self::DASHICON_PREFIX . 'pets';
    /** @var string https://developer.wordpress.org/resource/dashicons/#printer */
    case printer = self::DASHICON_PREFIX . 'printer';
    /** @var string https://developer.wordpress.org/resource/dashicons/#privacy */
    case privacy = self::DASHICON_PREFIX . 'privacy';
    /** @var string https://developer.wordpress.org/resource/dashicons/#superhero */
    case superhero_1 = self::DASHICON_PREFIX . 'superhero';
    /** @var string https://developer.wordpress.org/resource/dashicons/#superhero-alt */
    case superhero_2 = self::DASHICON_PREFIX . 'superhero-alt';
}
