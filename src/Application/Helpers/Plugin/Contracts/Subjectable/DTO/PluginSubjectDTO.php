<?php declare(strict_types=1);

namespace Wordless\Application\Helpers\Plugin\Contracts\Subjectable\DTO;

use Wordless\Application\Helpers\Plugin;
use Wordless\Application\Helpers\Plugin\Contracts\Subjectable\DTO\PluginSubjectDTO\Exceptions\PluginNotFound;
use Wordless\Infrastructure\Helper\Contracts\Subjectable\DTO\SubjectDTO;

final class PluginSubjectDTO extends SubjectDTO
{
    private array $data;
    private bool $is_active;
    private bool $is_must_use;

    /**
     * @param mixed $subject
     * @throws PluginNotFound
     */
    public function __construct(mixed $subject)
    {
        parent::__construct($subject);

        $this->of($this->subject);
    }

    public function isActive(): bool
    {
        return $this->is_active
            ?? $this->is_active = $this->data[Plugin::DATA_IS_ACTIVE] ?? Plugin::isActive($this->subject);
    }

    public function isMustUse(): bool
    {
        return $this->is_must_use ?? $this->is_must_use = Plugin::isMustUse($this->subject);
    }

    /**
     * @param string $subject
     * @return $this
     * @throws PluginNotFound
     */
    public function of(string $subject): self
    {
        if (empty($this->data = Plugin::data($subject))) {
            throw new PluginNotFound($subject);
        }

        $this->subject = $this->data[Plugin::DATA_ID] ?? throw new PluginNotFound($subject);
        unset($this->is_active);
        unset($this->is_must_use);

        return $this;
    }
}
