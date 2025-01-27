<?php declare(strict_types=1);

namespace Wordless\Wordpress\Models\Attachment\DTO\MediaDTO\DTO;

use Wordless\Application\Helpers\ProjectPath;
use Wordless\Application\Helpers\ProjectPath\Exceptions\PathNotFoundException;

readonly class SizeDTO
{
    final public const TYPE_ORIGINAL = 'original';
    final public const KEY_FILE = 'file';
    final public const KEY_FILESIZE = 'filesize';
    final public const KEY_HEIGHT = 'height';
    final public const KEY_WIDTH = 'width';

    public function __construct(
        public string $type,
        public string $filename,
        public string $url,
        public int    $width,
        public int    $height,
        public int    $filesize
    )
    {
    }

    /**
     * @return string
     * @throws PathNotFoundException
     */
    final public function absoluteFilepath(): string
    {
        return ProjectPath::wpUploads($this->filename);
    }

    final public function isOriginal(): bool
    {
        return $this->type === SizeDTO::TYPE_ORIGINAL;
    }
}
