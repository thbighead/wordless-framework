<?php declare(strict_types=1);

namespace Wordless\Application\Helpers\ProjectPath\Contracts\Subjectable\DTO\ProjectPathSubjectDTO\Traits;

use Wordless\Application\Helpers\ProjectPath;
use Wordless\Application\Helpers\ProjectPath\Exceptions\PathNotFoundException;

trait Internal
{
    protected array $relative_from = [];
    protected array $relative_to = [];

    /**
     * @param string $subject
     * @throws PathNotFoundException
     */
    public function __construct(string $subject)
    {
        parent::__construct(ProjectPath::realpath($subject));
    }

    public function getOriginalSubject(): string
    {
        return parent::getOriginalSubject();
    }

    public function getSubject(): string
    {
        return parent::getSubject();
    }
}
