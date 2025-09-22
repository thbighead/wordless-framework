<?php declare(strict_types=1);

namespace Wordless\Infrastructure\Wordpress\Taxonomy\Exceptions;

use Throwable;
use Wordless\Exceptions\WpErrorException;
use Wordless\Infrastructure\Wordpress\Taxonomy;
use Wordless\Wordpress\Models\Contracts\IRelatedMetaData;
use WP_Error;

class FailedDisaggregatingObject extends WpErrorException
{
    public function __construct(
        public readonly IRelatedMetaData|int $object,
        public readonly Taxonomy             $term,
        WP_Error|false                       $requestError,
        ?Throwable                           $previous = null
    )
    {
        if ($requestError === false) {
            $requestError = new WP_Error(
                'unknown_error',
                "Unknown error when disaggregating {$this->term->name} ({$this->term->taxonomy} of ID {$this->term->id()}) to object of ID {$this->object->id()}."
            );
        }

        parent::__construct($requestError, $previous);
    }
}
