<?php declare(strict_types=1);

namespace Wordless\Core\Bootstrapper\Traits;

use InvalidArgumentException;
use Symfony\Component\Dotenv\Exception\FormatException;
use Symfony\Component\Dotenv\Exception\PathException;
use Wordless\Application\Helpers\Config\Contracts\Subjectable\DTO\ConfigSubjectDTO\Exceptions\EmptyConfigKey;
use Wordless\Application\Helpers\ProjectPath\Exceptions\PathNotFoundException;
use Wordless\Core\Bootstrapper;
use Wordless\Core\Bootstrapper\Exceptions\InvalidProviderClass;
use Wordless\Core\Bootstrapper\Traits\Entities\Traits\InstallCustomPostStatuses;
use Wordless\Core\Bootstrapper\Traits\Entities\Traits\InstallCustomPostTypes;
use Wordless\Core\Bootstrapper\Traits\Entities\Traits\InstallCustomTaxonomies;
use Wordless\Core\Exceptions\DotEnvNotSetException;
use Wordless\Infrastructure\Wordpress\CustomPost\Traits\Register\Exceptions\CustomPostTypeRegistrationFailed;
use Wordless\Infrastructure\Wordpress\CustomPost\Traits\Register\Traits\Validation\Exceptions\InvalidCustomPostTypeKey;
use Wordless\Infrastructure\Wordpress\CustomPost\Traits\Register\Traits\Validation\Exceptions\ReservedCustomPostTypeKey;
use Wordless\Infrastructure\Wordpress\CustomPostStatus\Traits\Register\Traits\Validation\Exceptions\ReservedCustomPostStatusKey;
use Wordless\Infrastructure\Wordpress\Taxonomy\CustomTaxonomy\Traits\Register\Exceptions\CustomTaxonomyRegistrationFailed;
use Wordless\Infrastructure\Wordpress\Taxonomy\CustomTaxonomy\Traits\Register\Exceptions\InvalidObjectTypeAssociationToTaxonomy;
use Wordless\Infrastructure\Wordpress\Taxonomy\CustomTaxonomy\Traits\Register\Traits\Validation\Exceptions\InvalidCustomTaxonomyName;
use Wordless\Infrastructure\Wordpress\Taxonomy\CustomTaxonomy\Traits\Register\Traits\Validation\Exceptions\ReservedCustomTaxonomyName;

trait Entities
{
    use InstallCustomPostStatuses;
    use InstallCustomPostTypes;
    use InstallCustomTaxonomies;

    /**
     * @return void
     * @throws CustomPostTypeRegistrationFailed
     * @throws CustomTaxonomyRegistrationFailed
     * @throws DotEnvNotSetException
     * @throws EmptyConfigKey
     * @throws FormatException
     * @throws InvalidArgumentException
     * @throws InvalidCustomPostTypeKey
     * @throws InvalidCustomTaxonomyName
     * @throws InvalidObjectTypeAssociationToTaxonomy
     * @throws InvalidProviderClass
     * @throws PathException
     * @throws PathNotFoundException
     * @throws ReservedCustomPostStatusKey
     * @throws ReservedCustomPostTypeKey
     * @throws ReservedCustomTaxonomyName
     */
    public static function registerEntities(): void
    {
        Bootstrapper::getInstance()
            ->resolveCustomTaxonomies()
            ->resolveCustomPostStatuses()
            ->resolveCustomPostTypes();
    }
}
