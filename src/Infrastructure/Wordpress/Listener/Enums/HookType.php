<?php

namespace Wordless\Infrastructure\Wordpress\Listener\Enums;

enum HookType
{
    case action;
    case filter;
}
