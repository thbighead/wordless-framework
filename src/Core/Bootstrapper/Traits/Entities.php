<?php declare(strict_types=1);

namespace Wordless\Core\Bootstrapper\Traits;

use RuntimeException;
use Wordless\Core\Bootstrapper;
use Wordless\Core\Bootstrapper\Exceptions\FailedToLoadBootstrapper;
use Wordless\Core\Bootstrapper\Traits\Entities\Exceptions\FailedToRegisterWordlessEntity;
use Wordless\Core\Bootstrapper\Traits\Entities\Traits\InstallCustomPostStatuses;
use Wordless\Core\Bootstrapper\Traits\Entities\Traits\InstallCustomPostTypes;
use Wordless\Core\Bootstrapper\Traits\Entities\Traits\InstallCustomPostTypes\Exceptions\FailedToResolveCustomPostTypeRegistrar;
use Wordless\Core\Bootstrapper\Traits\Entities\Traits\InstallCustomTaxonomies;
use Wordless\Infrastructure\Wordpress\Registrar\CustomPostStatusRegistrar\Traits\Validation\Exceptions\ReservedCustomPostStatusKey;
use Wordless\Infrastructure\Wordpress\Taxonomy\CustomTaxonomy\Traits\Register\Exceptions\CustomTaxonomyRegistrationFailed;

trait Entities
{
    use InstallCustomPostStatuses;
    use InstallCustomPostTypes;
    use InstallCustomTaxonomies;

    /**
     * @return void
     * @throws FailedToRegisterWordlessEntity
     * @throws RuntimeException
     */
    public static function registerEntities(): void
    {
        try {
            Bootstrapper::getInstance()
                ->resolveCustomTaxonomies()
                ->resolveCustomPostStatuses()
                ->resolveCustomPostTypes();
        } catch (FailedToLoadBootstrapper
        |FailedToResolveCustomPostTypeRegistrar
        |ReservedCustomPostStatusKey
        |CustomTaxonomyRegistrationFailed $exception) {
            throw new FailedToRegisterWordlessEntity($exception);
        }
    }
}
