<?php

namespace Wordless\Commands;

use Symfony\Component\Console\Command\Command;
use Wordless\Abstractions\Commands\SyncAcfCommand;

class CleanAcfDatabaseGroups extends SyncAcfCommand
{
    protected static $defaultName = 'acf:clean';

    protected function description(): string
    {
        return 'Clean your ACF groups from database.';
    }

    protected function runIt(): int
    {
        $removed_groups_count = 0;

        foreach (acf_get_raw_field_groups() as $db_acf_group) {
            if ($acf_group_key = $db_acf_group['key'] ?? false) {
                $group_title = $this->getGroupTitle($db_acf_group);
                $this->wrapScriptWithMessagesWhenVerbose(
                    "Removing group $group_title ($acf_group_key)...",
                    function () use ($acf_group_key) {
                        acf_delete_field_group($acf_group_key);
                    }
                );
                $removed_groups_count++;
            }
        }

        $this->output->writeln("Removed $removed_groups_count old ACF groups from database.");

        return Command::SUCCESS;
    }
}
