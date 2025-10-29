<?php declare(strict_types=1);

namespace Wordless\Wordpress\Models\Comment\Traits\Crud\Traits\CreateAndUpdate\Builder\UpdateBuilder\Exceptions;

use Throwable;
use Wordless\Exceptions\WpErrorException;
use WP_Error;

class WpUpdateCommentFailed extends WpErrorException
{
    public function __construct(public readonly array $arguments, WP_Error $requestError, ?Throwable $previous = null)
    {
        parent::__construct($requestError, $previous);
    }
}
