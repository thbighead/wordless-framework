<?php declare(strict_types=1);

namespace Wordless\Wordpress\Models\Attachment\DTO;

use Symfony\Component\Dotenv\Exception\FormatException;
use Wordless\Application\Helpers\Arr;
use Wordless\Application\Helpers\Environment\Exceptions\CannotResolveEnvironmentGet;
use Wordless\Application\Helpers\Environment\Exceptions\DotEnvNotSetException;
use Wordless\Application\Helpers\Link;
use Wordless\Application\Helpers\ProjectPath;
use Wordless\Application\Helpers\ProjectPath\Contracts\Subjectable\DTO\ProjectPathSubjectDTO\FilePathSubjectDTO;
use Wordless\Application\Helpers\ProjectPath\Exceptions\PathNotFoundException;
use Wordless\Application\Helpers\Str;
use Wordless\Wordpress\Models\Attachment;
use Wordless\Wordpress\Models\Attachment\DTO\MediaDTO\DTO\SizeDTO;

readonly class MediaDTO
{
    public string $mimetype;
    public string $relative_upload_filepath;
    public string $relative_upload_filepath_without_extension;
    /** @var SizeDTO[] $sizes */
    public array $sizes;
    public string $url;
    public FilePathSubjectDTO $filepath;

    /**
     * @param array $raw_data
     * @throws CannotResolveEnvironmentGet
     * @throws PathNotFoundException
     */
    public function __construct(public array $raw_data)
    {
        $this->mimetype = $this->raw_data[Attachment::KEY_MIME_TYPE] ?? null;
        $this->relative_upload_filepath = $this->raw_data[SizeDTO::KEY_FILE] ?? null;
        $this->filepath = new FilePathSubjectDTO(ProjectPath::wpUploads($this->relative_upload_filepath));
        $this->relative_upload_filepath_without_extension = Str::beforeLast(
            $this->relative_upload_filepath,
            $this->filepath->getExtension()
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
     * @throws CannotResolveEnvironmentGet
     */
    private function setSizes(): void
    {
        $original_width = $this->raw_data[SizeDTO::KEY_WIDTH] ?? null;
        $original_height = $this->raw_data[SizeDTO::KEY_HEIGHT] ?? null;
        $sizes = [];

        if (is_int($original_width) && is_int($original_height)) {
            $base_original_uploads_url = Link::uploads(Str::beforeLast(
                $this->relative_upload_filepath_without_extension,
                '/'
            ));
            $raw_sizes = $this->raw_data['sizes'] ?? [];

            $raw_sizes[SizeDTO::TYPE_ORIGINAL] = [
                SizeDTO::KEY_FILE => Str::afterLast($this->filepath->getSubject(), DIRECTORY_SEPARATOR),
                SizeDTO::KEY_FILESIZE => $this->raw_data[SizeDTO::KEY_FILESIZE] ?? null,
                SizeDTO::KEY_WIDTH => $original_width,
                SizeDTO::KEY_HEIGHT => $original_height,
            ];

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
        }

        $this->sizes = $sizes;
    }
}
