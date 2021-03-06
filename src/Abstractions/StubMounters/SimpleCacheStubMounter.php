<?php

namespace Wordless\Abstractions\StubMounters;

use Wordless\Helpers\Debugger;

class SimpleCacheStubMounter extends BaseStubMounter
{
    /**
     * @param array $replace_content_dictionary
     * @return $this
     */
    public function setReplaceContentDictionary(array $replace_content_dictionary): BaseStubMounter
    {
        $this->replace_content_dictionary = [
            '/*simple-array*/' => Debugger::variableExport($replace_content_dictionary)
        ];

        return $this;
    }

    protected function relativeStubFilename(): string
    {
        return 'simple-return-simple-array-script.php';
    }
}