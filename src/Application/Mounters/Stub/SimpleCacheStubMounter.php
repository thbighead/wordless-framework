<?php

namespace Wordless\Application\Mounters\Stub;

use Wordless\Application\Helpers\Debugger;
use Wordless\Infrastructure\Mounters\StubMounter;

class SimpleCacheStubMounter extends StubMounter
{
    /**
     * @param array $replace_content_dictionary
     * @return $this
     */
    public function setReplaceContentDictionary(array $replace_content_dictionary): StubMounter
    {
        $this->replace_content_dictionary = [
            '/*simple-array*/' => Debugger::variableExport($replace_content_dictionary)
        ];

        return $this;
    }

    protected function relativeStubFilename(): string
    {
        return 'simple-return-simple-array-script';
    }
}
