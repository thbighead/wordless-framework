<?php declare(strict_types=1);

namespace Wordless\Infrastructure\Enums;

use Wordless\Application\Helpers\Str;
use Wordless\Infrastructure\Enums\MimeType\Exceptions\InvalidMimeType;

enum MimeType: string
{
    final public const VALIDATION_REGEX = '/^[a-z0-9_]+\/[^\/\sA-Z]+$/';

    case application_epubzip = 'application/epub+zip';
    case application_gzip = 'application/gzip';
    case application_javaarchive = 'application/java-archive';
    case application_json = 'application/json';
    case application_ldjson = 'application/ld+json';
    case application_msword = 'application/msword';
    case application_octetstream = 'application/octet-stream';
    case application_ogg = 'application/ogg';
    case application_pdf = 'application/pdf';
    case application_rtf = 'application/rtf';
    case application_vndamazonebook = 'application/vnd.amazon.ebook';
    case application_vndappleinstallerxml = 'application/vnd.apple.installer+xml';
    case application_vndmozillaxulxml = 'application/vnd.mozilla.xul+xml';
    case application_vndmsexcel = 'application/vnd.ms-excel';
    case application_vndmsfontobject = 'application/vnd.ms-fontobject';
    case application_vndmspowerpoint = 'application/vnd.ms-powerpoint';
    case application_vndoasisopendocumentpresentation = 'application/vnd.oasis.opendocument.presentation';
    case application_vndoasisopendocumentspreadsheet = 'application/vnd.oasis.opendocument.spreadsheet';
    case application_vndoasisopendocumenttext = 'application/vnd.oasis.opendocument.text';
    case application_vndopenxmlformatsofficedocumentpresentationmlpresentation = 'application/vnd.openxmlformats-officedocument.presentationml.presentation';
    case application_vndopenxmlformatsofficedocumentspreadsheetmlsheet = 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet';
    case application_vndopenxmlformatsofficedocumentwordprocessingmldocument = 'application/vnd.openxmlformats-officedocument.wordprocessingml.document';
    case application_vndrar = 'application/vnd.rar';
    case application_vndvisio = 'application/vnd.visio';
    case application_x7zcompressed = 'application/x-7z-compressed';
    case application_xabiword = 'application/x-abiword';
    case application_xbzip = 'application/x-bzip';
    case application_xbzip2 = 'application/x-bzip2';
    case application_xcdf = 'application/x-cdf';
    case application_xcsh = 'application/x-csh';
    case application_xhtmlxml = 'application/xhtml+xml';
    case application_xhttpdphp = 'application/x-httpd-php';
    case application_xml = 'application/xml';
    case application_xsh = 'application/x-sh';
    case application_xshockwaveflash = 'application/x-shockwave-flash';
    case application_xtar = 'application/x-tar';
    case application_zip = 'application/zip';
    case audio_3gpp = 'audio/3gpp';
    case audio_3gpp2 = 'audio/3gpp2';
    case audio_aac = 'audio/aac';
    case audio_midi = 'audio/midi';
    case audio_mpeg = 'audio/mpeg';
    case audio_ogg = 'audio/ogg';
    case audio_opus = 'audio/opus';
    case audio_wav = 'audio/wav';
    case audio_webm = 'audio/webm';
    case audio_xmidi = 'audio/x-midi';
    case font_otf = 'font/otf';
    case font_ttf = 'font/ttf';
    case font_woff = 'font/woff';
    case font_woff2 = 'font/woff2';
    case image_avif = 'image/avif';
    case image_bmp = 'image/bmp';
    case image_gif = 'image/gif';
    case image_jpeg = 'image/jpeg';
    case image_png = 'image/png';
    case image_svgxml = 'image/svg+xml';
    case image_tiff = 'image/tiff';
    case image_vndmicrosofticon = 'image/vnd.microsoft.icon';
    case image_webp = 'image/webp';
    case text_calendar = 'text/calendar';
    case text_css = 'text/css';
    case text_csv = 'text/csv';
    case text_html = 'text/html';
    case text_javascript = 'text/javascript';
    case text_plain = 'text/plain';
    case video_3gpp = 'video/3gpp';
    case video_3gpp2 = 'video/3gpp2';
    case video_mp2t = 'video/mp2t';
    case video_mp4 = 'video/mp4';
    case video_mpeg = 'video/mpeg';
    case video_ogg = 'video/ogg';
    case video_webm = 'video/webm';
    case video_xmsvideo = 'video/x-msvideo';

    /**
     * @param string $supposed_mime_type
     * @return string
     * @throws InvalidMimeType
     */
    public static function validate(string $supposed_mime_type): string
    {
        if (preg_match(self::VALIDATION_REGEX, $supposed_mime_type) !== 1) {
            throw new InvalidMimeType($supposed_mime_type);
        }

        return $supposed_mime_type;
    }

    public function getFileExtension(): string
    {
        return match ($this) {
            self::application_epubzip => 'epub',
            self::application_gzip => 'gz',
            self::application_javaarchive => 'jar',
            self::application_json => 'json',
            self::application_ldjson => 'jsonld',
            self::application_msword => 'doc',
            self::application_octetstream => 'bin',
            self::application_ogg => 'ogx',
            self::application_pdf => 'pdf',
            self::application_rtf => 'rtf',
            self::application_vndamazonebook => 'azw',
            self::application_vndappleinstallerxml => 'mpkg',
            self::application_vndmozillaxulxml => 'xul',
            self::application_vndmsexcel => 'xls',
            self::application_vndmsfontobject => 'eot',
            self::application_vndmspowerpoint => 'ppt',
            self::application_vndoasisopendocumentpresentation => 'odp',
            self::application_vndoasisopendocumentspreadsheet => 'ods',
            self::application_vndoasisopendocumenttext => 'odt',
            self::application_vndopenxmlformatsofficedocumentpresentationmlpresentation => 'pptx',
            self::application_vndopenxmlformatsofficedocumentspreadsheetmlsheet => 'xlsx',
            self::application_vndopenxmlformatsofficedocumentwordprocessingmldocument => 'docx',
            self::application_vndrar => 'rar',
            self::application_vndvisio => 'vsd',
            self::application_x7zcompressed => '7z',
            self::application_xabiword => 'abw',
            self::application_xbzip => 'bz',
            self::application_xbzip2 => 'bz2',
            self::application_xcdf => 'cda',
            self::application_xcsh => 'csh',
            self::application_xhtmlxml => 'xhtml',
            self::application_xhttpdphp => 'php',
            self::application_xml => 'xml',
            self::application_xsh => 'sh',
            self::application_xshockwaveflash => 'svf',
            self::application_xtar => 'tar',
            self::application_zip => 'zip',
            self::audio_3gpp, self::video_3gpp => '3gp',
            self::audio_3gpp2, self::video_3gpp2 => '3g2',
            self::audio_aac => 'aac',
            self::audio_midi, self::audio_xmidi => 'midi',
            self::audio_mpeg => 'mp3',
            self::audio_ogg => 'oga',
            self::audio_opus => 'opus',
            self::audio_wav => 'wav',
            self::audio_webm => 'weba',
            self::font_otf => 'otf',
            self::font_ttf => 'ttf',
            self::font_woff => 'woff',
            self::font_woff2 => 'woff2',
            self::image_avif => 'avif',
            self::image_bmp => 'bmp',
            self::image_gif => 'gif',
            self::image_jpeg => 'jpg',
            self::image_png => 'png',
            self::image_svgxml => 'svg',
            self::image_tiff => 'tiff',
            self::image_vndmicrosofticon => 'ico',
            self::image_webp => 'webp',
            self::text_calendar => 'ics',
            self::text_css => 'css',
            self::text_csv => 'csv',
            self::text_html => 'html',
            self::text_javascript => 'js',
            self::text_plain => 'txt',
            self::video_mp2t => 'ts',
            self::video_mp4 => 'mp4',
            self::video_mpeg => 'mpeg',
            self::video_ogg => 'ogv',
            self::video_webm => 'webm',
            self::video_xmsvideo => 'avi',
        };
    }

    public function getFileTypeName(): string
    {
        return match ($this) {
            self::application_epubzip => 'Electronic publication (EPUB)',
            self::application_gzip => 'GZip Compressed Archive',
            self::application_javaarchive => 'Java Archive (JAR)',
            self::application_json => 'JSON format',
            self::application_ldjson => 'JSON-LD format',
            self::application_msword => 'Microsoft Word',
            self::application_octetstream => 'Any kind of binary data',
            self::application_ogg => 'OGG',
            self::application_pdf => 'Adobe Portable Document Format (PDF)',
            self::application_rtf => 'Rich Text Format (RTF)',
            self::application_vndamazonebook => 'Amazon Kindle eBook format',
            self::application_vndappleinstallerxml => 'Apple Installer Package',
            self::application_vndmozillaxulxml => 'XUL',
            self::application_vndmsexcel => 'Microsoft Excel',
            self::application_vndmsfontobject => 'MS Embedded OpenType fonts',
            self::application_vndmspowerpoint => 'Microsoft PowerPoint',
            self::application_vndoasisopendocumentpresentation => 'OpenDocument presentation document',
            self::application_vndoasisopendocumentspreadsheet => 'OpenDocument spreadsheet document',
            self::application_vndoasisopendocumenttext => 'OpenDocument text document',
            self::application_vndopenxmlformatsofficedocumentpresentationmlpresentation => 'Microsoft PowerPoint (OpenXML)',
            self::application_vndopenxmlformatsofficedocumentspreadsheetmlsheet => 'Microsoft Excel (OpenXML)',
            self::application_vndopenxmlformatsofficedocumentwordprocessingmldocument => 'Microsoft Word (OpenXML)',
            self::application_vndrar => 'RAR archive',
            self::application_vndvisio => 'Microsoft Visio',
            self::application_x7zcompressed => '7-zip archive',
            self::application_xabiword => 'AbiWord document',
            self::application_xbzip => 'BZip archive',
            self::application_xbzip2 => 'BZip2 archive',
            self::application_xcdf => 'CD audio',
            self::application_xcsh => 'C-Shell script',
            self::application_xhtmlxml => 'XHTML',
            self::application_xhttpdphp => 'PHP',
            self::application_xml => 'XML',
            self::application_xsh => 'Bourne shell script',
            self::application_xshockwaveflash => 'Adobe Flash document',
            self::application_xtar => 'Tape Archive (TAR)',
            self::application_zip => 'ZIP archive',
            self::audio_3gpp => '3GPP audio container',
            self::audio_3gpp2 => '3GPP2 audio container',
            self::audio_aac => 'AAC audio',
            self::audio_midi, self::audio_xmidi => 'Musical Instrument Digital Interface (MIDI)',
            self::audio_mpeg => 'MP3 audio',
            self::audio_ogg => 'OGG audio',
            self::audio_opus => 'Opus audio',
            self::audio_wav => 'Waveform Audio Format',
            self::audio_webm => 'WEBM audio',
            self::font_otf => 'OpenType font',
            self::font_ttf => 'TrueType Font',
            self::font_woff => 'Web Open Font Format (WOFF)',
            self::font_woff2 => 'Web Open Font Format 2 (WOFF2)',
            self::image_avif => 'AVIF image',
            self::image_bmp => 'Windows OS/2 Bitmap Graphics',
            self::image_gif => 'Graphics Interchange Format (GIF)',
            self::image_jpeg => 'JPEG images',
            self::image_png => 'Portable Network Graphics',
            self::image_svgxml => 'Scalable Vector Graphics (SVG)',
            self::image_tiff => 'Tagged Image File Format (TIFF)',
            self::image_vndmicrosofticon => 'Icon format',
            self::image_webp => 'WEBP image',
            self::text_calendar => 'iCalendar format',
            self::text_css => 'Cascading Style Sheets (CSS)',
            self::text_csv => 'Comma-separated values (CSV)',
            self::text_html => 'HyperText Markup Language (HTML)',
            self::text_javascript => 'JavaScript',
            self::text_plain => 'Text (generally ASCII or ISO 8859-n)',
            self::video_3gpp => '3GPP video container',
            self::video_3gpp2 => '3GPP2 video container',
            self::video_mp2t => 'MPEG transport stream',
            self::video_mp4 => 'MP4 video',
            self::video_mpeg => 'MPEG Video',
            self::video_ogg => 'OGG video',
            self::video_webm => 'WEBM video',
            self::video_xmsvideo => 'AVI: Audio Video Interleave',
        };
    }

    public function getMimeGroup(): string
    {
        return Str::before($this->value, '/');
    }
}
