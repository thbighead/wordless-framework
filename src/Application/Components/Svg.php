<?php declare(strict_types=1);

namespace Wordless\Application\Components;

use Wordless\Application\Components\Svg\Exceptions\InvalidSvgContent;
use Wordless\Application\Helpers\DirectoryFiles;
use Wordless\Application\Helpers\DirectoryFiles\Exceptions\FailedToGetFileContent;
use Wordless\Application\Helpers\ProjectPath;
use Wordless\Application\Helpers\ProjectPath\Exceptions\PathNotFoundException;
use Wordless\Application\Libraries\Component\Component;
use Wordless\Infrastructure\Wordpress\EnqueueableAsset\EnqueueableScript;
use Wordless\Infrastructure\Wordpress\EnqueueableAsset\EnqueueableStyle;
use Wordless\Infrastructure\Wordpress\EnqueueableAsset\Enums\StandardContext;

class Svg extends Component
{
    readonly public string $filtered_svg_content;
    readonly public ?string $svg_absolute_file_path;

    /**
     * @param string $svg
     * @param StandardContext $context
     * @return static
     * @throws FailedToGetFileContent
     * @throws InvalidSvgContent
     */
    public static function make(string $svg, StandardContext $context = StandardContext::no_context): static
    {
        return new static($svg, $context);
    }

    /**
     * @param string $svg
     * @param StandardContext $context
     * @throws FailedToGetFileContent
     * @throws InvalidSvgContent
     */
    public function __construct(string $svg, StandardContext $context = StandardContext::no_context)
    {
        $this->setSvgData($svg);
        parent::__construct($context);
    }

    protected function script(): ?EnqueueableScript
    {
        return null;
    }

    protected function style(): ?EnqueueableStyle
    {
        return null;
    }

    protected function template(): string
    {
        return $this->filtered_svg_content;
    }

    /**
     * @param string $svg
     * @return void
     * @throws FailedToGetFileContent
     * @throws InvalidSvgContent
     */
    private function setSvgData(string $svg): void
    {
        try {
            $svg_content = DirectoryFiles::getFileContent($this->svg_absolute_file_path = ProjectPath::realpath($svg));
        } catch (PathNotFoundException) {
            $this->svg_absolute_file_path = null;
            $svg_content = $svg;
        }

        if (($svg_content_index = strpos($svg_content, '<svg ')) === false) {
            throw new InvalidSvgContent($svg_content);
        }

        $this->filtered_svg_content = substr($svg_content, $svg_content_index);
    }
}
