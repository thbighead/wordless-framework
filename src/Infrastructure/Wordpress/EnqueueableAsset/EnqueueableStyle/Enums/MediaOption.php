<?php

namespace Wordless\Infrastructure\Wordpress\EnqueueableAsset\EnqueueableStyle\Enums;

enum MediaOption: string
{
    case ALL = 'all';
    case PRINT = 'print';
    case SCREEN = 'screen';
}
