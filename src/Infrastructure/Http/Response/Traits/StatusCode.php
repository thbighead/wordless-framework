<?php declare(strict_types=1);

namespace Wordless\Infrastructure\Http\Response\Traits;

use InvalidArgumentException;
use Wordless\Infrastructure\Http\Response\Enums\StatusCode as StatusCodeEnum;

trait StatusCode
{
    public function statusCode(bool $as_int = false): int|StatusCodeEnum|null
    {
        $status_code = $this->retrieveResponseArray()['code'] ?? null;

        return $as_int ? $status_code : StatusCodeEnum::tryFrom($status_code) ?? $status_code;
    }

    /**
     * @param bool $prefer_original
     * @return string
     * @throws InvalidArgumentException
     */
    public function statusCodeText(bool $prefer_original = false): string
    {
        if ($prefer_original && !empty($original_status_code_text = $this->retrieveOriginalStatusCodeText())) {
            return $original_status_code_text;
        }

        if (($status_code_text = $this->statusCode()) instanceof StatusCodeEnum) {
            return $status_code_text->asText();
        }

        return $this->retrieveOriginalStatusCodeText() ?? "$status_code_text";
    }

    private function retrieveResponseArray(): array
    {
        return $this->raw_response['response'] ?? [];
    }

    private function retrieveOriginalStatusCodeText(): ?string
    {
        return $this->retrieveResponseArray()['message'] ?? null;
    }
}
