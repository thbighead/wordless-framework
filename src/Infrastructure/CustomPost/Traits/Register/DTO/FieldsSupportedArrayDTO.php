<?php

namespace Wordless\Infrastructure\CustomPost\Traits\Register\DTO;

use Wordless\Contracts\ArrayDTO;
use Wordless\Infrastructure\CustomPost\Traits\Register\DTO\FieldsSupportedArrayDTO\Enums\CustomPostTypeFieldSupported;

final class FieldsSupportedArrayDTO extends ArrayDTO
{
    /** @var string[] $data */
    protected ?array $data = [];
    /** @var CustomPostTypeFieldSupported[] $supported */
    private array $supported = [];

    public function getData(): ?array
    {
        $this->supported = [];

        foreach ($this->data as $fieldSupported) {
            /** @var CustomPostTypeFieldSupported $fieldSupported */
            $this->supported[] = $fieldSupported->value;
        }

        return empty($this->supported) ? null : $this->supported;
    }

    /**
     * @return CustomPostTypeFieldSupported[]|null
     */
    public function getSupported(): ?array
    {
        if (!empty($this->supported)) {
            return $this->supported;
        }

        return $this->supported = $this->getData() ?? [];
    }

    public function supportAuthor(): FieldsSupportedArrayDTO
    {
        $this->data[CustomPostTypeFieldSupported::author->name] = CustomPostTypeFieldSupported::author;

        return $this;
    }

    public function supportComments(): FieldsSupportedArrayDTO
    {
        $this->data[CustomPostTypeFieldSupported::comments->name] = CustomPostTypeFieldSupported::comments;

        return $this;
    }

    public function supportContent(): FieldsSupportedArrayDTO
    {
        return $this->supportEditor();
    }

    public function supportCustom(): FieldsSupportedArrayDTO
    {
        return $this->supportCustomFields();
    }

    public function supportCustomFields(): FieldsSupportedArrayDTO
    {
        $this->data[CustomPostTypeFieldSupported::custom->name] = CustomPostTypeFieldSupported::custom;

        return $this;
    }

    public function supportEditor(): FieldsSupportedArrayDTO
    {
        $this->data[CustomPostTypeFieldSupported::content->name] = CustomPostTypeFieldSupported::content;

        return $this;
    }

    public function supportExcerpt(): FieldsSupportedArrayDTO
    {
        $this->data[CustomPostTypeFieldSupported::excerpt->name] = CustomPostTypeFieldSupported::excerpt;

        return $this;
    }

    public function supportFeaturedImage(): FieldsSupportedArrayDTO
    {
        return $this->supportThumbnail();
    }

    public function supportFeaturedThumbnail(): FieldsSupportedArrayDTO
    {
        return $this->supportThumbnail();
    }

    public function supportFeaturedThumbnailImage(): FieldsSupportedArrayDTO
    {
        return $this->supportThumbnail();
    }

    public function supportFormats(): FieldsSupportedArrayDTO
    {
        return $this->supportPostFormats();
    }

    public function supportHierarchicalFields(): FieldsSupportedArrayDTO
    {
        $this->data[CustomPostTypeFieldSupported::hierarchical_fields->name] =
            CustomPostTypeFieldSupported::hierarchical_fields;

        return $this;
    }

    public function supportPageAttributes(): FieldsSupportedArrayDTO
    {
        $this->data[CustomPostTypeFieldSupported::hierarchical_fields->name] =
            CustomPostTypeFieldSupported::hierarchical_fields;

        return $this;
    }

    public function supportPostFormats(): FieldsSupportedArrayDTO
    {
        $this->data[CustomPostTypeFieldSupported::post_formats->name] =
            CustomPostTypeFieldSupported::post_formats;

        return $this;
    }

    public function supportRevisions(): FieldsSupportedArrayDTO
    {
        $this->data[CustomPostTypeFieldSupported::revisions->name] =
            CustomPostTypeFieldSupported::revisions;

        return $this;
    }

    public function supportThumbnail(): FieldsSupportedArrayDTO
    {
        $this->data[CustomPostTypeFieldSupported::featured_thumbnail_image->name] =
            CustomPostTypeFieldSupported::featured_thumbnail_image;

        return $this;
    }

    public function supportTitle(): FieldsSupportedArrayDTO
    {
        $this->data[CustomPostTypeFieldSupported::title->name] = CustomPostTypeFieldSupported::title;

        return $this;
    }

    public function supportTrackbacks(): FieldsSupportedArrayDTO
    {
        $this->data[CustomPostTypeFieldSupported::track_backs->name] =
            CustomPostTypeFieldSupported::track_backs;

        return $this;
    }
}
