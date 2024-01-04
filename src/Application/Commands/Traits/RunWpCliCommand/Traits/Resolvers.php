<?php

namespace Wordless\Application\Commands\Traits\RunWpCliCommand\Traits;

use Symfony\Component\Console\Exception\InvalidArgumentException;
use Wordless\Application\Commands\Traits\AllowRootMode;
use Wordless\Application\Commands\Traits\AllowRootMode\DTO\AllowRootModeOptionDTO;

trait Resolvers
{
    use AllowRootMode;

    /**
     * @param string $wp_cli_command
     * @return $this
     * @throws InvalidArgumentException
     */
    private function resolveWpCliCommandAllowRootMode(string &$wp_cli_command): static
    {
        if ($this->isAllowRootMode()) {
            $wp_cli_command = "$wp_cli_command --" . AllowRootModeOptionDTO::ALLOW_ROOT_MODE;
        }

        return $this;
    }

    private function resolveWpCliCommandDebugMode(string &$wp_cli_command): static
    {
        if ($this->isVVV()) {
            $wp_cli_command = "$wp_cli_command --debug";
        }

        return $this;
    }

    /**
     * @param string $wp_cli_command
     * @return $this
     * @throws InvalidArgumentException
     */
    private function resolveCommandModifiers(string &$wp_cli_command): static
    {
        return $this->resolveWpCliCommandAllowRootMode($wp_cli_command)
            ->resolveWpCliCommandDebugMode($wp_cli_command);
    }
}
