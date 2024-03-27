<?php declare(strict_types=1);

namespace Wordless\Application\Libraries\Mail\Message;

use Wordless\Application\Helpers\DirectoryFiles;
use Wordless\Application\Helpers\DirectoryFiles\Exceptions\NotAPhpFile;
use Wordless\Application\Helpers\ProjectPath;
use Wordless\Application\Helpers\ProjectPath\Exceptions\PathNotFoundException;

abstract class Template
{
    private string $message_template_path;
    /** @var array<string, string> $variables_dictionary */
    private array $variables_dictionary = [];

    /**
     * @param string $message_template_path
     * @throws PathNotFoundException
     */
    public function __construct(string $message_template_path)
    {
        $this->message_template_path = ProjectPath::realpath($message_template_path);
    }

    /**
     * @return string
     * @throws NotAPhpFile
     * @throws PathNotFoundException
     */
    public function render(): string
    {
        return DirectoryFiles::getFileEcho($this->message_template_path, $this->variables_dictionary);
    }

    public function setVariable(string $variable_name, string $variable_value): static
    {
        $this->variables_dictionary[$variable_name] = $variable_value;

        return $this;
    }
}
