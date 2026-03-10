<?php declare(strict_types=1);

namespace Wordless\Application\Helpers\ProjectPath\Contracts\Subjectable\DTO\ProjectPathSubjectDTO;

use Wordless\Application\Helpers\Arr;
use Wordless\Application\Helpers\Arr\Contracts\Subjectable\DTO\ArraySubjectDTO;
use Wordless\Application\Helpers\DirectoryFiles;
use Wordless\Application\Helpers\DirectoryFiles\Exceptions\FailedToCreateDirectory;
use Wordless\Application\Helpers\DirectoryFiles\Exceptions\FailedToDeletePath;
use Wordless\Application\Helpers\DirectoryFiles\Exceptions\FailedToGetFileContent;
use Wordless\Application\Helpers\DirectoryFiles\Exceptions\FailedToPutFileContent;
use Wordless\Application\Helpers\DirectoryFiles\Exceptions\NotAPhpFile;
use Wordless\Application\Helpers\ProjectPath\Contracts\Subjectable\DTO\ProjectPathSubjectDTO;
use Wordless\Application\Helpers\ProjectPath\Contracts\Subjectable\DTO\ProjectPathSubjectDTO\FilePathSubjectDTO\Exceptions\InvalidJsonFile;
use Wordless\Application\Helpers\ProjectPath\Exceptions\PathNotFoundException;
use Wordless\Application\Helpers\Str;
use Wordless\Application\Helpers\Str\Exceptions\JsonDecodeError;

final class FilePathSubjectDTO extends ProjectPathSubjectDTO
{
    private string $content;
    private string $extension;
    private string $php_echo;

    /**
     * @return null
     * @throws FailedToDeletePath
     * @throws PathNotFoundException
     */
    public function delete(): null
    {
        parent::delete();

        unset($this->content);
        unset($this->extension);
        unset($this->php_echo);

        return null;
    }

    /**
     * @return string
     * @throws FailedToGetFileContent
     * @throws PathNotFoundException
     */
    public function getContent(): string
    {
        return $this->content ?? $this->content = DirectoryFiles::getFileContent($this->subject);
    }

    public function getExtension(): string
    {
        return $this->extension ?? $this->extension = Str::afterLast($this->subject, '.');
    }

    /**
     * @return ArraySubjectDTO
     * @throws InvalidJsonFile
     */
    public function getJsonContent(): ArraySubjectDTO
    {
        try {
            return Arr::of(Str::jsonDecode($this->getContent()));
        } catch (FailedToGetFileContent|JsonDecodeError|PathNotFoundException $exception) {
            throw new InvalidJsonFile($this, $exception);
        }
    }

    /**
     * @return string
     * @throws NotAPhpFile
     * @throws PathNotFoundException
     */
    public function getPhpEcho(): string
    {
        return $this->php_echo ?? $this->php_echo = DirectoryFiles::getFileEcho($this->subject);
    }

    /**
     * @return bool
     * @throws FailedToGetFileContent
     * @throws PathNotFoundException
     */
    public function isJson(): bool
    {
        return Str::isJson($this->getContent());
    }

    /**
     * @param string $content
     * @param bool $overwrite
     * @return $this
     * @throws FailedToCreateDirectory
     * @throws FailedToPutFileContent
     */
    public function writeContent(string $content, bool $overwrite = true): self
    {
        DirectoryFiles::createFileAt($this->subject, $content, !$overwrite);

        if ($overwrite) {
            $this->content = $content;
        }

        return $this;
    }
}
