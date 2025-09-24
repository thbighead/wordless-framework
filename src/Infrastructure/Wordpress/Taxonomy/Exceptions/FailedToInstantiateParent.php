<?php declare(strict_types=1);

namespace Wordless\Infrastructure\Wordpress\Taxonomy\Exceptions;

use RuntimeException;
use Throwable;
use Wordless\Infrastructure\Enums\ExceptionCode;
use Wordless\Infrastructure\Wordpress\Taxonomy;

class FailedToInstantiateParent extends RuntimeException
{
    public function __construct(public readonly Taxonomy $term, public readonly bool $with_acfs, ?Throwable $previous = null)
    {
        $with_acfs_text = $this->with_acfs ? 'with ACFs' : 'without ACFs';

        parent::__construct(
            'Failed to construct ' . $term::class . " object $with_acfs_text",
            ExceptionCode::intentional_interrupt->value,
            $previous
        );
    }
}
