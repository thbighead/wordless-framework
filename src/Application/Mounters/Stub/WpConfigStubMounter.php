<?php

namespace Wordless\Application\Mounters\Stub;

use Wordless\Application\Helpers\ProjectPath;
use Wordless\Application\Helpers\ProjectPath\Exceptions\PathNotFoundException;
use Wordless\Application\Helpers\Str;
use Wordless\Application\Mounters\Stub\WpConfigStubMounter\Exceptions\WpConfigAlreadySet;
use Wordless\Infrastructure\Mounters\StubMounter;
use Wordless\Infrastructure\Mounters\StubMounter\Exceptions\FailedToCopyStub;

class WpConfigStubMounter extends StubMounter
{
    /**
     * @param string|null $new_file_path
     * @return void
     * @throws FailedToCopyStub
     * @throws WpConfigAlreadySet
     */
    public function mountNewFile(?string $new_file_path = null): void
    {
        try {
            $supposed_already_existing_wp_config_filepath = ProjectPath::realpath($new_file_path);

            if (Str::contains(file_get_contents($new_file_path), '@author Wordless')) {
                throw new WpConfigAlreadySet($supposed_already_existing_wp_config_filepath);
            }
        } catch (PathNotFoundException) {
            parent::mountNewFile($new_file_path);
        }
    }

    protected function relativeStubFilename(): string
    {
        return 'wp-config.php';
    }
}
