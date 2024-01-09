<?php

namespace Wordless\Tests;

use PHPUnit\Framework\TestCase;

abstract class WordlessTestCase extends TestCase
{
    public static function setUpBeforeClass(): void
    {
        require_once __DIR__ . '/../wp/wp-core/wp-config.php';

        parent::setUpBeforeClass();
    }
}
