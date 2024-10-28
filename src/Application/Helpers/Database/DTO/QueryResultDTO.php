<?php

namespace Wordless\Application\Helpers\Database\DTO;

use Wordless\Application\Helpers\Arr;

readonly class QueryResultDTO
{
    /** @var object[]|object $results */
    public array|object $results;

    public function __construct(
        public int|true     $affected_rows,
        array|object $results
    )
    {
        if (is_array($results) && count($results) === 1) {
            $results = Arr::first($results);
        }

        $this->results = $results;
    }
}
