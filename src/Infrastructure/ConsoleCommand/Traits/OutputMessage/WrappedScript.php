<?php

namespace Wordless\Infrastructure\ConsoleCommand\Traits\OutputMessage;

trait WrappedScript
{
    protected function wrapScriptWithMessages(
        string   $before_script_message,
        callable $script,
        string   $after_script_message = self::DONE_MESSAGE,
        bool     $only_when_verbose = false
    )
    {
        $only_when_verbose ?
            $this->writeWhenVerbose($before_script_message) : $this->write($before_script_message);

        $result = $script();

        $only_when_verbose ?
            $this->writelnSuccessWhenVerbose($after_script_message) : $this->writelnSuccess($after_script_message);

        return $result;
    }

    protected function wrapScriptWithMessagesWhenVerbose(
        string   $before_script_message,
        callable $script,
        string   $after_script_message = self::DONE_MESSAGE
    ): void
    {
        $this->wrapScriptWithMessages(
            $before_script_message,
            $script,
            $after_script_message,
            true
        );
    }
}
