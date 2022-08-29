<?php

namespace Wordless\Exceptions;

use Throwable;
use Symfony\Component\HttpFoundation\File\Exception\CannotWriteFileException;

class FailedToGenerateAcfGroupPhpFile extends CannotWriteFileException
{
	public function __construct(string $filepath, string $content, ?Throwable $previous = null)
	{
		parent::__construct(
			"Failed to write at $filepath with the following content:\n$content",
			0,
			$previous
		);
	}
}
