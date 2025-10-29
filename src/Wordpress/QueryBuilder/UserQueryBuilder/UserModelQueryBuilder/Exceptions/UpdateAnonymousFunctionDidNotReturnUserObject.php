<?php declare(strict_types=1);

namespace Wordless\Wordpress\QueryBuilder\UserQueryBuilder\UserModelQueryBuilder\Exceptions;

use BadMethodCallException;
use Throwable;
use Wordless\Infrastructure\Enums\ExceptionCode;
use Wordless\Wordpress\Models\User;

class UpdateAnonymousFunctionDidNotReturnUserObject extends BadMethodCallException
{
    public function __construct(?Throwable $previous = null)
    {
        parent::__construct(
            'The callable function parameter at update must return a ' . User::class . ' object.',
            ExceptionCode::development_error->value,
            $previous
        );
    }
}
