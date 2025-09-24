<?php declare(strict_types=1);

namespace Wordless\Application\Mounters\Stub;

use Wordless\Application\Helpers\Environment;
use Wordless\Application\Helpers\Environment\Exceptions\CannotResolveEnvironmentGet;
use Wordless\Infrastructure\Mounters\StubMounter;

class RobotsTxtStubMounter extends StubMounter
{
    public const STUB_FINAL_FILENAME = 'robots.txt';
    public const STUB_PROD_FILENAME = 'robots_prod.txt';
    public const STUB_NON_PROD_FILENAME = 'robots_non_prod.txt';

    /**
     * @return string
     * @throws CannotResolveEnvironmentGet
     */
    protected function relativeStubFilename(): string
    {
        return Environment::isProduction()
            ? self::STUB_PROD_FILENAME
            : self::STUB_NON_PROD_FILENAME;
    }
}
