<?php declare(strict_types=1);

namespace Wordless\Infrastructure\Mounters;

use Wordless\Application\Helpers\DirectoryFiles;
use Wordless\Application\Helpers\DirectoryFiles\Exceptions\FailedToCreateDirectory;
use Wordless\Application\Helpers\DirectoryFiles\Exceptions\FailedToGetDirectoryPermissions;
use Wordless\Application\Helpers\DirectoryFiles\Exceptions\FailedToPutFileContent;
use Wordless\Application\Helpers\ProjectPath;
use Wordless\Application\Helpers\ProjectPath\Exceptions\PathNotFoundException;
use Wordless\Application\Helpers\Str;
use Wordless\Infrastructure\Mounters\StubMounter\Exceptions\FailedToCopyStub;

abstract class StubMounter
{
    protected array $replace_content_dictionary;
    private string $new_file_path;
    private string $stub_filepath;
    private string $stub_unfilled_content;

    abstract protected function relativeStubFilename(): string;

    /**
     * @param string $new_file_path
     * @return static
     * @throws PathNotFoundException
     */
    public static function make(string $new_file_path): static
    {
        return new static($new_file_path);
    }

    /**
     * @param string $new_file_path
     * @throws PathNotFoundException
     */
    public function __construct(string $new_file_path)
    {
        $this->stub_unfilled_content = file_get_contents(
            $this->stub_filepath = ProjectPath::stubs($this->relativeStubFilename())
        );
        $this->new_file_path = $new_file_path;
    }

    /**
     * @param string|null $new_file_path
     * @return void
     * @throws FailedToCopyStub
     */
    public function mountNewFile(?string $new_file_path = null): void
    {
        $new_file_path = $new_file_path ?? $this->new_file_path;

        try {
            DirectoryFiles::createFileAt($new_file_path, $this->replaceUnfilledContent(), false);
        } catch (
        FailedToPutFileContent|FailedToCreateDirectory|FailedToGetDirectoryPermissions|PathNotFoundException $exception
        ) {
            throw new FailedToCopyStub($this->stub_filepath, $new_file_path, false, $exception);
        }
    }

    /**
     * @param array $replace_content_dictionary
     * @return $this
     */
    public function setReplaceContentDictionary(array $replace_content_dictionary): StubMounter
    {
        $this->replace_content_dictionary = $replace_content_dictionary;

        return $this;
    }

    private function replaceUnfilledContent(): string
    {
        if (empty($this->replace_content_dictionary)) {
            return $this->stub_unfilled_content;
        }

        $search = [];
        $replace = [];

        foreach ($this->replace_content_dictionary as $original_content => $new_content_value) {
            $search[] = $original_content;
            $replace[] = $new_content_value;
        }

        return Str::replace($this->stub_unfilled_content, $search, $replace);
    }
}
