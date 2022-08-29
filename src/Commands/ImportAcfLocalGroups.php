<?php

namespace Wordless\Commands;

use Exception;
use Symfony\Component\Console\Command\Command;
use Wordless\Abstractions\Commands\SyncAcfCommand;

class ImportAcfLocalGroups extends SyncAcfCommand
{
    protected static $defaultName = 'acf:import';

    protected function description(): string
    {
        return 'Import your local ACF groups to database.';
    }

    /**
     * @return int
     * @throws Exception
     */
    protected function runIt(): int
    {
        $local_groups_count = 0;
        $local_group_fields_count = 0;

        foreach (acf_get_local_field_groups() as $acf_group_key => $acf_group) {
            $group_title = $this->getGroupTitle($acf_group);
            $acf_group['fields'] = acf_get_fields($acf_group_key);
            $how_many_group_fields = count($acf_group['fields']);

            $this->wrapScriptWithMessagesWhenVerbose(
                "Importing local group $group_title ($acf_group_key) with $how_many_group_fields fields to database...",
                function () use ($acf_group) {
                    acf_import_field_group($acf_group);
                }
            );

            $local_groups_count++;
            $local_group_fields_count += $how_many_group_fields;
        }

        $this->output->writeln(
            "Inserted $local_groups_count local ACF groups with a total of $local_group_fields_count fields to database."
        );

        return Command::SUCCESS;
    }
}
