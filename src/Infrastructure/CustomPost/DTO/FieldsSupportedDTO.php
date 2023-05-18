<?php

namespace Wordless\Infrastructure\CustomPost\DTO;

use Wordless\Contracts\DTO\Traits\EmptyMaked;
use Wordless\Enums\CustomPostTypeField;
use Wordless\Infrastructure\DTO;

final class FieldsSupportedDTO extends DTO
{
    use EmptyMaked;

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

    public function supportAuthor(): FieldsSupportedDTO
    {
        $this->data[CustomPostTypeField::AUTHOR->name] = CustomPostTypeField::AUTHOR;

        return $this;
    }

    public function supportComments(): FieldsSupportedDTO
    {
        $this->data[CustomPostTypeField::COMMENTS->name] = CustomPostTypeField::COMMENTS;

        return $this;
    }

    public function supportContent(): FieldsSupportedDTO
    {
        return $this->supportEditor();
    }

    public function supportCustom(): FieldsSupportedDTO
    {
        return $this->supportCustomFields();
    }

    public function supportCustomFields(): FieldsSupportedDTO
    {
        $this->data[CustomPostTypeField::CUSTOM->name] = CustomPostTypeField::CUSTOM;

        return $this;
    }

    public function supportEditor(): FieldsSupportedDTO
    {
        $this->data[CustomPostTypeField::CONTENT->name] = CustomPostTypeField::CONTENT;

        return $this;
    }

    public function supportExcerpt(): FieldsSupportedDTO
    {
        $this->data[CustomPostTypeField::EXCERPT->name] = CustomPostTypeField::EXCERPT;

        return $this;
    }

    public function supportFeaturedImage(): FieldsSupportedDTO
    {
        return $this->supportThumbnail();
    }

    public function supportFeaturedThumbnail(): FieldsSupportedDTO
    {
        return $this->supportThumbnail();
    }

    public function supportFeaturedThumbnailImage(): FieldsSupportedDTO
    {
        return $this->supportThumbnail();
    }

    public function supportFormats(): FieldsSupportedDTO
    {
        return $this->supportPostFormats();
    }

    public function supportHierarchicalFields(): FieldsSupportedDTO
    {
        $this->data[CustomPostTypeField::HIERARCHICAL_FIELDS->name] = CustomPostTypeField::HIERARCHICAL_FIELDS;

        return $this;
    }

    public function supportPageAttributes(): FieldsSupportedDTO
    {
        $this->data[CustomPostTypeField::HIERARCHICAL_FIELDS->name] = CustomPostTypeField::HIERARCHICAL_FIELDS;

        return $this;
    }

    public function supportPostFormats(): FieldsSupportedDTO
    {
        $this->data[CustomPostTypeField::POST_FORMATS->name] = CustomPostTypeField::POST_FORMATS;

        return $this;
    }

    public function supportRevisions(): FieldsSupportedDTO
    {
        $this->data[CustomPostTypeField::REVISIONS->name] = CustomPostTypeField::REVISIONS;

        return $this;
    }

    public function supportThumbnail(): FieldsSupportedDTO
    {
        $this->data[CustomPostTypeField::FEATURED_THUMBNAIL_IMAGE->name] =
            CustomPostTypeField::FEATURED_THUMBNAIL_IMAGE;

        return $this;
    }

    public function supportTitle(): FieldsSupportedDTO
    {
        $this->data[CustomPostTypeField::TITLE->name] = CustomPostTypeField::TITLE;

        return $this;
    }

    public function supportTrackbacks(): FieldsSupportedDTO
    {
        $this->data[CustomPostTypeField::TRACK_BACKS->name] = CustomPostTypeField::TRACK_BACKS;

        return $this;
    }
}
