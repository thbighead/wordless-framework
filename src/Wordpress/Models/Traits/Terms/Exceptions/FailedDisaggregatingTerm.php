<?php declare(strict_types=1);

namespace Wordless\Wordpress\Models\Traits\Terms\Exceptions;

use Throwable;
use Wordless\Exceptions\WpErrorException;
use Wordless\Wordpress\Models\Contracts\IRelatedMetaData;
use WP_Error;

class FailedDisaggregatingTerm extends WpErrorException
{
    public function __construct(
        public readonly IRelatedMetaData|int $object,
        public readonly string               $term_taxonomy,
        WP_Error|false                       $requestError,
        ?Throwable                           $previous = null
    )
    {
        if ($requestError === false) {
            $requestError = new WP_Error(
                'unknown_error',
                "Unknown error when disaggregating $this->term_taxonomy terms to object of ID {$this->object->id()}."
            );
        }

        parent::__construct($requestError, $previous);
    }
}
