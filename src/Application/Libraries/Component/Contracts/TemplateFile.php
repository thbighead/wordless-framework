<?php declare(strict_types=1);

namespace Wordless\Application\Libraries\Component\Contracts;

interface TemplateFile
{
    /**
     * @return array<string, mixed>
     */
    public function templateVariables(): array;
}
