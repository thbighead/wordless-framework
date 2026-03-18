<?php declare(strict_types=1);

namespace Wordless\Application\Libraries\Component;

use Wordless\Application\Helpers\ProjectPath;
use Wordless\Application\Helpers\ProjectPath\Exceptions\FailedToGetWordpressTheme;
use Wordless\Application\Helpers\ProjectPath\Exceptions\PathNotFoundException;
use Wordless\Application\Helpers\Str;
use Wordless\Application\Helpers\Template;
use Wordless\Application\Libraries\Component\Contracts\TemplateFile;
use Wordless\Application\Libraries\Component\Exceptions\InvalidTemplatePathing;
use Wordless\Infrastructure\Wordpress\EnqueueableAsset;
use Wordless\Infrastructure\Wordpress\EnqueueableAsset\EnqueueableScript;
use Wordless\Infrastructure\Wordpress\EnqueueableAsset\EnqueueableStyle;
use Wordless\Infrastructure\Wordpress\EnqueueableAsset\Enums\StandardContext;

abstract class StaticComponent extends BaseComponent
{
    abstract protected function template(): string;

    readonly private string $html;

    /**
     * @return string
     * @throws InvalidTemplatePathing
     */
    final public function html(): string
    {
        return $this->html ?? $this->html = $this->mountHtml();
    }

    /**
     * @return string
     * @throws InvalidTemplatePathing
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
     * @throws InvalidTemplatePathing
     */
    private function validateTemplateRelativePath(): string
    {
        $template_relative_path = Str::finishWith($this->template(), '.php');

        try {
            ProjectPath::theme($template_relative_path);
        } catch (FailedToGetWordpressTheme|PathNotFoundException $exception) {
            throw new InvalidTemplatePathing($template_relative_path, $exception);
        }

        return $template_relative_path;
    }
}
