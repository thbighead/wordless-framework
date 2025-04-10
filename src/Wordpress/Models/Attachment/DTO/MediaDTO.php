<?php declare(strict_types=1);

namespace Wordless\Wordpress\Models\Attachment\DTO;

use Symfony\Component\Dotenv\Exception\FormatException;
use Wordless\Application\Helpers\Arr;
use Wordless\Application\Helpers\Link;
use Wordless\Application\Helpers\ProjectPath;
use Wordless\Application\Helpers\ProjectPath\Contracts\Subjectable\DTO\ProjectPathSubjectDTO;
use Wordless\Application\Helpers\ProjectPath\Exceptions\PathNotFoundException;
use Wordless\Application\Helpers\Str;
use Wordless\Core\Exceptions\DotEnvNotSetException;
use Wordless\Wordpress\Models\Attachment;
use Wordless\Wordpress\Models\Attachment\DTO\MediaDTO\DTO\SizeDTO;

readonly class MediaDTO
{
    final public const EXTENSION_DELIMITER = '.';

    public string $file_extension;
    public string $mimetype;
    public string $relative_upload_filepath;
    public string $relative_upload_filepath_without_extension;
    /** @var SizeDTO[] $sizes */
    public array $sizes;
    public string $url;
    public ProjectPathSubjectDTO $filepath;

    /**
     * @param array $raw_data
     * @throws DotEnvNotSetException
     * @throws FormatException
     */
    public function __construct(public array $raw_data)
    {
        $this->mimetype = $this->raw_data[Attachment::KEY_MIME_TYPE] ?? null;
        $this->filepath = new ProjectPathSubjectDTO();
        $this->relative_upload_filepath = $this->raw_data[SizeDTO::KEY_FILE] ?? null;
        $this->relative_upload_filepath_without_extension = Str::beforeLast(
            $this->relative_upload_filepath,
            self::EXTENSION_DELIMITER
        );
        $this->file_extension = self::EXTENSION_DELIMITER . Str::afterLast(
                $this->relative_upload_filepath,
                self::EXTENSION_DELIMITER
            );

        $this->setSizes();
    }

    /**
     * @return string
     * @throws PathNotFoundException
     */
    final public function absolutePath(): string
    {
        return ProjectPath::wpUploads($this->relative_upload_filepath);
    }

    final public function hasSizes(array|string $sizes = []): bool
    {
        if (empty($sizes)) {
            return !empty($this->sizes);
        }

        $sizes = Arr::wrap($sizes);

        foreach ($sizes as $size) {
            if (!key_exists($size, $this->sizes)) {
                return false;
            }
        }

        return true;
    }

    /**
     * @return void
     * @throws DotEnvNotSetException
     * @throws FormatException
     */
    private function setSizes(): void
    {
        $sizes = [];
        $base_original_uploads_url = Link::uploads(Str::beforeLast(
            $this->relative_upload_filepath_without_extension,
            '/'
        ));

        if (!empty($raw_sizes = $this->raw_data['sizes'] ?? [])) {
            $raw_sizes = array_merge([SizeDTO::TYPE_ORIGINAL => [
                SizeDTO::KEY_FILE => "$this->relative_upload_filepath_without_extension$this->file_extension",
                SizeDTO::KEY_FILESIZE => $this->raw_data[SizeDTO::KEY_FILESIZE] ?? null,
                SizeDTO::KEY_HEIGHT => $this->raw_data[SizeDTO::KEY_HEIGHT] ?? null,
                SizeDTO::KEY_WIDTH => $this->raw_data[SizeDTO::KEY_WIDTH] ?? null,
            ]], $raw_sizes);
        }

        foreach ($raw_sizes as $raw_size_type => $raw_size_data) {
            $sizes[$raw_size_type] = new SizeDTO(
                $raw_size_type,
                $filename = $raw_size_data[SizeDTO::KEY_FILE] ?? null,
                "$base_original_uploads_url/$filename",
                $raw_size_data[SizeDTO::KEY_WIDTH] ?? null,
                $raw_size_data[SizeDTO::KEY_HEIGHT] ?? null,
                $raw_size_data[SizeDTO::KEY_FILESIZE] ?? null
            );
        }

        uasort($sizes, function (SizeDTO $size1, SizeDTO $size2): int {
            return $size1->width - $size2->width;
        });

        $this->sizes = $sizes;
    }
}
