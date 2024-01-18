<?php

namespace Wordless\Wordpress\QueryBuilder\PostQueryBuilder\Traits\Type\Traits;

use Wordless\Application\Helpers\Arr;
use Wordless\Application\Helpers\Str;
use Wordless\Infrastructure\Enums\MimeType as MimeTypeEnum;
use Wordless\Infrastructure\Enums\MimeType\Exceptions\InvalidMimeType;
use Wordless\Wordpress\Models\PostType\Enums\StandardType;
use Wordless\Wordpress\QueryBuilder\PostQueryBuilder\Traits\Type\Traits\MimeType\Exceptions\MimeTypeNotAllowed;

trait MimeType
{
    private const KEY_ATTACHMENT_MIME_TYPE = 'post_mime_type';

    /**
     * @param MimeTypeEnum|string $mime_type
     * @param MimeTypeEnum|string ...$mime_types
     * @return $this
     * @throws InvalidMimeType
     * @throws MimeTypeNotAllowed
     */
    public function whereAttachmentsSupportsMimeType(
        MimeTypeEnum|string $mime_type,
        MimeTypeEnum|string ...$mime_types
    ): static
    {
        if (!$this->isWhereTypeIncludingAttachment()) {
            $this->whereType(StandardType::attachment);
        }

        if (!isset($this->arguments[self::KEY_ATTACHMENT_MIME_TYPE])) {
            $this->arguments[self::KEY_ATTACHMENT_MIME_TYPE] = [];
        }

        foreach (Arr::prepend($mime_types, $mime_type) as $mime_type) {
            $this->arguments[self::KEY_ATTACHMENT_MIME_TYPE][$this->validateMimeType($mime_type)] = $mime_type;
        }

        return $this;
    }

    /**
     * @param MimeTypeEnum|string $mime_type
     * @param MimeTypeEnum|string ...$mime_types
     * @return $this
     * @throws InvalidMimeType
     * @throws MimeTypeNotAllowed
     */
    public function whereAttachmentsNotSupportsMimeType(
        MimeTypeEnum|string $mime_type,
        MimeTypeEnum|string ...$mime_types
    ): static
    {
        $not_supported_mime_types = array_diff(get_allowed_mime_types(), Arr::prepend($mime_types, $mime_type));

        if (!empty($not_supported_mime_types)) {
            $this->whereAttachmentsSupportsMimeType(...$not_supported_mime_types);
        }

        return $this;
    }

    /**
     * @param MimeTypeEnum|string $mime_type
     * @return string
     * @throws InvalidMimeType
     * @throws MimeTypeNotAllowed
     */
    private function validateMimeType(MimeTypeEnum|string $mime_type): string
    {
        if ($mime_type instanceof MimeTypeEnum) {
            return $mime_type->value;
        }

        if (!in_array($mime_type = Str::lower($mime_type), get_allowed_mime_types())) {
            throw new MimeTypeNotAllowed($mime_type);
        }

        return MimeTypeEnum::validate(Str::lower($mime_type));
    }
}
