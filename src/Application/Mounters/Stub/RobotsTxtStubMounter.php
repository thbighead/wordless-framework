<?php

namespace Wordless\Abstractions\StubMounters;

use Wordless\Helpers\Environment;

class RobotsTxtStubMounter extends BaseStubMounter
{

    public const STUB_FINAL_FILENAME = 'robots.txt';
    public const STUB_PROD_FILENAME = 'robots_prod.txt';
    public const STUB_NON_PROD_FILENAME = 'robots_non_prod.txt';

    protected function relativeStubFilename(): string
    {
        return Environment::isProduction()
            ? self::STUB_PROD_FILENAME
            : self::STUB_NON_PROD_FILENAME;
    }
}
