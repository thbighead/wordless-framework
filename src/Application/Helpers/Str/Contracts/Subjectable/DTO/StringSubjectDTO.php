<?php declare(strict_types=1);

namespace Wordless\Application\Helpers\Str\Contracts\Subjectable\DTO;

use Wordless\Application\Helpers\Crypto;
use Wordless\Application\Helpers\Crypto\Exceptions\DecryptionFailed;
use Wordless\Application\Helpers\Environment\Exceptions\CannotResolveEnvironmentGet;
use Wordless\Application\Helpers\Str\Contracts\Subjectable\DTO\StringSubjectDTO\Traits\HelperMethods;
use Wordless\Application\Helpers\Str\Contracts\Subjectable\DTO\StringSubjectDTO\Traits\Internal;
use Wordless\Infrastructure\Helper\Contracts\Subjectable\DTO\SubjectDTO;

final class StringSubjectDTO extends SubjectDTO
{
    use HelperMethods;
    use Internal;

    /**
     * @return $this
     * @throws DecryptionFailed
     */
    public function decrypt(): self
    {
        $this->subject = Crypto::decrypt($this->subject);

        return $this;
    }

    /**
     * @return $this
     * @throws CannotResolveEnvironmentGet
     */
    public function encrypt(): self
    {
        $this->subject = Crypto::encrypt($this->subject);

        return $this;
    }
}
