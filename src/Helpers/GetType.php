<?php

namespace Wordless\Helpers;

class GetType
{
	public const INTEGER = 'integer';
	public const STRING = 'string';

	public static function of($variable): string
	{
		$type = gettype($variable);

		if ($type === 'object') {
			return get_class($variable);
		}

		return $type;
	}
}
