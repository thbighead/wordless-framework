<?php declare(strict_types=1);

namespace Wordless\Application\Libraries\Component;

use Wordless\Application\Helpers\Config\Contracts\Subjectable\DTO\ConfigSubjectDTO\Exceptions\EmptyConfigKey;
use Wordless\Application\Helpers\ProjectPath;
use Wordless\Application\Helpers\ProjectPath\Exceptions\PathNotFoundException;
use Wordless\Application\Helpers\Str;
use Wordless\Application\Helpers\Template;
use Wordless\Application\Libraries\Component\Contracts\TemplateFile;
use Wordless\Application\Libraries\Component\Exceptions\TemplateNotFoundException;
use Wordless\Infrastructure\Wordpress\EnqueueableAsset;
use Wordless\Infrastructure\Wordpress\EnqueueableAsset\EnqueueableScript;
use Wordless\Infrastructure\Wordpress\EnqueueableAsset\EnqueueableStyle;

abstract class Component
{
    abstract protected function script(): ?EnqueueableScript;

    abstract protected function style(): ?EnqueueableStyle;

    abstract protected function template(): string;

    private static array $already_loaded_assets = [];
    private string $html;

    public function __construct()
    {
        $this->loadAssets();
    }

    /**
     * @return string
     * @throws EmptyConfigKey
     * @throws TemplateNotFoundException
     */
    final public function html(): string
    {
        return $this->html ?? $this->html = $this->mountHtml();
    }

    private function enqueueAsset(?EnqueueableAsset $asset): void
    {
        if (!is_null($asset) && !isset(self::$already_loaded_assets[$asset::class])) {
            $asset->enqueue();
            self::$already_loaded_assets[$asset::class] = $asset::class;
        }
    }

    private function loadAssets(): void
    {
        $this->loadScript()->loadStyle();
    }

    private function loadScript(): static
    {
        $this->enqueueAsset($this->script());

        return $this;
    }

    private function loadStyle(): void
    {
        $this->enqueueAsset($this->style());
    }

    /**
     * @return string
     * @throws EmptyConfigKey
     * @throws TemplateNotFoundException
     */
    private function mountHtml(): string
    {
        $template = $this->template();

        if (!($this instanceof TemplateFile)) {
            return $template;
        }

        ob_start();
        Template::includeTemplate(
            $this->validateTemplateRelativePath(),
            ['fields' => $this->componentInstanceFields()]
        );
        $template = ob_get_contents();
        ob_end_clean();

        return $template;
    }

    /**
     * @return string
     * @throws EmptyConfigKey
     * @throws TemplateNotFoundException
     */
    private function validateTemplateRelativePath(): string
    {
        $template_relative_path = Str::finishWith($this->template(), '.php');

        try {
            ProjectPath::theme($template_relative_path);
        } catch (PathNotFoundException $exception) {
            throw new TemplateNotFoundException($template_relative_path, $exception);
        }

        return $template_relative_path;
    }
}
