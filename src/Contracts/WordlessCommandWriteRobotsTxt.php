<?php

namespace Wordless\Contracts;

use Wordless\Exception\PathNotFoundException;
use Wordless\Helpers\ProjectPath;
use Wordless\Helpers\Str;

trait WordlessCommandWriteRobotsTxt
{
    /**
     * @param string $filename
     * @param string $new_robots_txt_filepath
     * @return void
     * @throws PathNotFoundException
     */
    private function mountRobotsTxtFromStub(string $filename, string $new_robots_txt_filepath)
    {
        $robots_txt_content = file_get_contents(ProjectPath::stubs($filename));

        preg_match_all('/{(\S+)}/', $robots_txt_content, $replaceable_values_regex_result);
        $env_variables_to_replace_into_robots_txt_stub = $replaceable_values_regex_result[1] ?? [];

        if (empty($env_variables_to_replace_into_robots_txt_stub)) {
            file_put_contents($new_robots_txt_filepath, $robots_txt_content);
            return;
        }

        $robots_txt_content = str_replace(
            $replaceable_values_regex_result[0] ?? [],
            array_map(function ($env_variable_name) {
                $env_variable_value = $this->getEnvVariableByKey($env_variable_name, '');

                return str_contains($env_variable_name, 'URL') ?
                    Str::finishWith($env_variable_value, '/') : $env_variable_value;
            }, $env_variables_to_replace_into_robots_txt_stub),
            $robots_txt_content
        );

        file_put_contents($new_robots_txt_filepath, $robots_txt_content);
    }
}