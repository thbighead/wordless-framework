<?php

namespace Wordless\Infrastructure\CustomPost\DTO;

use Wordless\Contracts\ArrayDTO;
use Wordless\Enums\CustomPostTypeField;

final class FieldsSupportedArrayDTO extends ArrayDTO
{
    /** @var string[] $data */
    protected mixed $data = [];
    /** @var CustomPostTypeField[] $supported */
    private array $supported = [];

    public function getData(): ?array
    {
        $this->supported = [];

        foreach ($this->data as $fieldSupported) {
            /** @var CustomPostTypeField $fieldSupported */
            $this->supported[] = $fieldSupported->value;
        }

        return empty($this->supported) ? null : $this->supported;
    }

    /**
     * @return CustomPostTypeField[]|null
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
        $this->data[CustomPostTypeField::AUTHOR->name] = CustomPostTypeField::AUTHOR;

        return $this;
    }

    public function supportComments(): FieldsSupportedArrayDTO
    {
        $this->data[CustomPostTypeField::COMMENTS->name] = CustomPostTypeField::COMMENTS;

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
        $this->data[CustomPostTypeField::CUSTOM->name] = CustomPostTypeField::CUSTOM;

        return $this;
    }

    public function supportEditor(): FieldsSupportedArrayDTO
    {
        $this->data[CustomPostTypeField::CONTENT->name] = CustomPostTypeField::CONTENT;

        return $this;
    }

    public function supportExcerpt(): FieldsSupportedArrayDTO
    {
        $this->data[CustomPostTypeField::EXCERPT->name] = CustomPostTypeField::EXCERPT;

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
        $this->data[CustomPostTypeField::HIERARCHICAL_FIELDS->name] = CustomPostTypeField::HIERARCHICAL_FIELDS;

        return $this;
    }

    public function supportPageAttributes(): FieldsSupportedArrayDTO
    {
        $this->data[CustomPostTypeField::HIERARCHICAL_FIELDS->name] = CustomPostTypeField::HIERARCHICAL_FIELDS;

        return $this;
    }

    public function supportPostFormats(): FieldsSupportedArrayDTO
    {
        $this->data[CustomPostTypeField::POST_FORMATS->name] = CustomPostTypeField::POST_FORMATS;

        return $this;
    }

    public function supportRevisions(): FieldsSupportedArrayDTO
    {
        $this->data[CustomPostTypeField::REVISIONS->name] = CustomPostTypeField::REVISIONS;

        return $this;
    }

    public function supportThumbnail(): FieldsSupportedArrayDTO
    {
        $this->data[CustomPostTypeField::FEATURED_THUMBNAIL_IMAGE->name] =
            CustomPostTypeField::FEATURED_THUMBNAIL_IMAGE;

        return $this;
    }

    public function supportTitle(): FieldsSupportedArrayDTO
    {
        $this->data[CustomPostTypeField::TITLE->name] = CustomPostTypeField::TITLE;

        return $this;
    }

    public function supportTrackbacks(): FieldsSupportedArrayDTO
    {
        $this->data[CustomPostTypeField::TRACK_BACKS->name] = CustomPostTypeField::TRACK_BACKS;

        return $this;
    }
}
