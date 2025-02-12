<?php declare(strict_types=1);

namespace Wordless\Application\Helpers\Str\Enums;

use Doctrine\Inflector\Language as DoctrineLanguage;

enum Language: string
{
    case english          = DoctrineLanguage::ENGLISH;
    case french           = DoctrineLanguage::FRENCH;
    case norwegian_bokmal = DoctrineLanguage::NORWEGIAN_BOKMAL;
    case portuguese       = DoctrineLanguage::PORTUGUESE;
    case spanish          = DoctrineLanguage::SPANISH;
    case turkish          = DoctrineLanguage::TURKISH;
}
