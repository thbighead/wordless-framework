<?php

namespace Wordless\Abstractions\Commands;

use Wordless\Adapters\WordlessCommand;
use Wordless\Contracts\Command\LoadWpConfig;

abstract class SyncAcfCommand extends WordlessCommand
{
	use LoadWpConfig;

	protected function arguments(): array
	{
		return [];
	}

	protected function getGroupTitle(array $group): string
	{
		return $group['title'] ?? 'Invalid Title';
	}

	protected function help(): string
	{
		return 'This is useful to synchronize ACFs throughout your VCS (Git).';
	}

	protected function options(): array
	{
		return [];
	}

	protected function wrapScriptWithMessagesWhenVerbose(string $before_script_message, callable $script)
	{
		$this->wrapScriptWithMessages($before_script_message, $script, ' Done!', true);
	}
}
