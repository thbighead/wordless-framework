<?php

namespace Wordless\Abstractions\Enums;

class Role
{
    public const ADMINISTRATOR = 'administrator';
    public const AUTHOR = 'author';
    public const CONTRIBUTOR = 'contributor';
    public const EDITOR = 'editor';
    public const KEY = 'role';
    public const SUBSCRIBER = 'subscriber';
    public const DEFAULT = [
        self::ADMINISTRATOR => self::ADMINISTRATOR,
        self::AUTHOR => self::AUTHOR,
        self::CONTRIBUTOR => self::CONTRIBUTOR,
        self::EDITOR => self::EDITOR,
        self::SUBSCRIBER => self::SUBSCRIBER,
    ];
}
