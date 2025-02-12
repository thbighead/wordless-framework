<?php declare(strict_types=1);

namespace Wordless\Application\Helpers\Str\Enums;

enum Encoding: string
{
    case UCS_4 = 'UCS-4';
    case UCS_4BE = 'UCS-4BE';
    case UCS_4LE = 'UCS-4LE';
    case UCS_2 = 'UCS-2';
    case UCS_2BE = 'UCS-2BE';
    case UCS_2LE = 'UCS-2LE';
    case UTF_32 = 'UTF-32';
    case UTF_32BE = 'UTF-32BE';
    case UTF_32LE = 'UTF-32LE';
    case UTF_16 = 'UTF-16';
    case UTF_16BE = 'UTF-16BE';
    case UTF_16LE = 'UTF-16LE';
    case UTF_7 = 'UTF-7';
    case UTF7_IMAP = 'UTF7-IMAP';
    case UTF_8 = 'UTF-8';
    case ASCII = 'ASCII';
    case EUC_JP = 'EUC-JP';
    case SJIS = 'SJIS';
    case eucJP_win = 'eucJP-win';
    case SJIS_win = 'SJIS-win';
    case ISO_2022_JP = 'ISO-2022-JP';
    case ISO_2022_JP_MS = 'ISO-2022-JP-MS';
    case CP932 = 'CP932';
    case CP51932 = 'CP51932';
    case SJIS_mac = 'SJIS-mac';
    case SJIS_Mobile_DOCOMO = 'SJIS-Mobile#DOCOMO';
    case SJIS_Mobile_KDDI = 'SJIS-Mobile#KDDI';
    case SJIS_Mobile_SOFTBANK = 'SJIS-Mobile#SOFTBANK';
    case UTF_8_Mobile_DOCOMO = 'UTF-8-Mobile#DOCOMO';
    case UTF_8_Mobile_KDDI_A = 'UTF-8-Mobile#KDDI-A';
    case UTF_8_Mobile_KDDI_B = 'UTF-8-Mobile#KDDI-B';
    case UTF_8_Mobile_SOFTBANK = 'UTF-8-Mobile#SOFTBANK';
    case ISO_2022_JP_MOBILE_KDDI = 'ISO-2022-JP-MOBILE#KDDI';
    case JIS = 'JIS';
    case JIS_ms = 'JIS-ms';
    case CP50220 = 'CP50220';
    case CP50220raw = 'CP50220raw';
    case CP50221 = 'CP50221';
    case CP50222 = 'CP50222';
    case ISO_8859_1 = 'ISO-8859-1';
    case ISO_8859_2 = 'ISO-8859-2';
    case ISO_8859_3 = 'ISO-8859-3';
    case ISO_8859_4 = 'ISO-8859-4';
    case ISO_8859_5 = 'ISO-8859-5';
    case ISO_8859_6 = 'ISO-8859-6';
    case ISO_8859_7 = 'ISO-8859-7';
    case ISO_8859_8 = 'ISO-8859-8';
    case ISO_8859_9 = 'ISO-8859-9';
    case ISO_8859_10 = 'ISO-8859-10';
    case ISO_8859_13 = 'ISO-8859-13';
    case ISO_8859_14 = 'ISO-8859-14';
    case ISO_8859_15 = 'ISO-8859-15';
    case ISO_8859_16 = 'ISO-8859-16';
    case byte2be = 'byte2be';
    case byte2le = 'byte2le';
    case byte4be = 'byte4be';
    case byte4le = 'byte4le';
    case BASE64 = 'BASE64';
    case HTML_ENTITIES = 'HTML-ENTITIES';
    case seven_bit = '7bit';
    case eight_bit = '8bit';
    case EUC_CN = 'EUC-CN';
    case CP936 = 'CP936';
    case GB18030 = 'GB18030';
    case HZ = 'HZ';
    case EUC_TW = 'EUC-TW';
    case CP950 = 'CP950';
    case BIG_5 = 'BIG-5';
    case EUC_KR = 'EUC-KR';
    case UHC = 'UHC';
    case ISO_2022_KR = 'ISO-2022-KR';
    case Windows_1251 = 'Windows-1251';
    case Windows_1252 = 'Windows-1252';
    case CP866 = 'CP866';
    case KOI8_R = 'KOI8-R';
    case KOI8_U = 'KOI8-U';
    case ArmSCII_8 = 'ArmSCII-8';
    // Aliases
    case MacJapanese = 'MacJapanese';
    case SJIS_DOCOMO = 'SJIS_DOCOMO';
    case SJIS_KDDI = 'SJIS_KDDI';
    case SJIS_SOFTBANK = 'SJIS_SOFTBANK';
    case UTF_8_DOCOMO = 'UTF_8_DOCOMO';
    case UTF_8_KDDI = 'UTF_8_KDDI';
    case UTF_8_SOFTBANK = 'UTF_8_SOFTBANK';
    case ISO_2022_JP_KDDI = 'ISO_2022_JP_KDDI';
    case HTML = 'HTML';
    case CP949 = 'CP949';
    case CP1251 = 'CP1251';
    case CP1252 = 'CP1252';
    case IBM866 = 'IBM866';
    case ArmSCII8 = 'ArmSCII8';

    public function alias(): ?self
    {
        return match ($this) {
            self::SJIS_mac => self::MacJapanese,
            self::SJIS_Mobile_DOCOMO => self::SJIS_DOCOMO,
            self::SJIS_Mobile_KDDI => self::SJIS_KDDI,
            self::SJIS_Mobile_SOFTBANK => self::SJIS_SOFTBANK,
            self::UTF_8_Mobile_DOCOMO => self::UTF_8_DOCOMO,
            self::UTF_8_Mobile_KDDI_B => self::UTF_8_KDDI,
            self::UTF_8_Mobile_SOFTBANK => self::UTF_8_SOFTBANK,
            self::ISO_2022_JP_MOBILE_KDDI => self::ISO_2022_JP_KDDI,
            self::HTML_ENTITIES => self::HTML,
            self::UHC => self::CP949,
            self::Windows_1251 => self::CP1251,
            self::Windows_1252 => self::CP1252,
            self::CP866 => self::IBM866,
            self::ArmSCII_8 => self::ArmSCII8,
            default => null
        };
    }

    public function aliasOf(): ?self
    {
        return match ($this) {
            self::MacJapanese => self::SJIS_mac,
            self::SJIS_DOCOMO => self::SJIS_Mobile_DOCOMO,
            self::SJIS_KDDI => self::SJIS_Mobile_KDDI,
            self::SJIS_SOFTBANK => self::SJIS_Mobile_SOFTBANK,
            self::UTF_8_DOCOMO => self::UTF_8_Mobile_DOCOMO,
            self::UTF_8_KDDI => self::UTF_8_Mobile_KDDI_B,
            self::UTF_8_SOFTBANK => self::UTF_8_Mobile_SOFTBANK,
            self::ISO_2022_JP_KDDI => self::ISO_2022_JP_MOBILE_KDDI,
            self::HTML => self::HTML_ENTITIES,
            self::CP949 => self::UHC,
            self::CP1251 => self::Windows_1251,
            self::CP1252 => self::Windows_1252,
            self::IBM866 => self::CP866,
            self::ArmSCII8 => self::ArmSCII_8,
            default => null
        };
    }
}
