<?php declare(strict_types=1);

namespace Wordless\Application\Listeners;

use InvalidArgumentException;
use Symfony\Component\Dotenv\Exception\FormatException;
use Wordless\Application\Helpers\Config\Contracts\Subjectable\DTO\ConfigSubjectDTO\Exceptions\EmptyConfigKey;
use Wordless\Application\Helpers\Environment\Exceptions\DotEnvNotSetException;
use Wordless\Application\Helpers\ProjectPath\Exceptions\PathNotFoundException;
use Wordless\Core\Bootstrapper;
use Wordless\Core\Bootstrapper\Exceptions\InvalidProviderClass;
use Wordless\Infrastructure\Wordpress\CustomPost\Traits\Register\Exceptions\CustomPostTypeRegistrationFailed;
use Wordless\Infrastructure\Wordpress\CustomPost\Traits\Register\Traits\Validation\Exceptions\InvalidCustomPostTypeKeyFormat;
use Wordless\Infrastructure\Wordpress\CustomPost\Traits\Register\Traits\Validation\Exceptions\ReservedCustomPostTypeKeyFormat;
use Wordless\Infrastructure\Wordpress\CustomPostStatus\Traits\Register\Traits\Validation\Exceptions\ReservedCustomPostStatusKey;
use Wordless\Infrastructure\Wordpress\Hook\Contracts\ActionHook;
use Wordless\Infrastructure\Wordpress\Listener\ActionListener;
use Wordless\Infrastructure\Wordpress\Taxonomy\CustomTaxonomy\Traits\Register\Exceptions\CustomTaxonomyRegistrationFailed;
use Wordless\Infrastructure\Wordpress\Taxonomy\CustomTaxonomy\Traits\Register\Exceptions\InvalidObjectTypeAssociationToTaxonomy;
use Wordless\Infrastructure\Wordpress\Taxonomy\CustomTaxonomy\Traits\Register\Traits\Validation\Exceptions\InvalidCustomTaxonomyNameFormat;
use Wordless\Infrastructure\Wordpress\Taxonomy\CustomTaxonomy\Traits\Register\Traits\Validation\Exceptions\ReservedCustomTaxonomyNameFormat;
use Wordless\Wordpress\Hook\Enums\Action;

class RegisterEntities extends ActionListener
{
    protected static function hook(): ActionHook
    {
        return Action::init;
    }

    /**
     * @return void
     * @throws CustomPostTypeRegistrationFailed
     * @throws CustomTaxonomyRegistrationFailed
     * @throws DotEnvNotSetException
     * @throws EmptyConfigKey
     * @throws FormatException
     * @throws InvalidArgumentException
     * @throws InvalidCustomPostTypeKeyFormat
     * @throws InvalidCustomTaxonomyNameFormat
     * @throws InvalidObjectTypeAssociationToTaxonomy
     * @throws InvalidProviderClass
     * @throws PathNotFoundException
     * @throws ReservedCustomPostStatusKey
     * @throws ReservedCustomPostTypeKeyFormat
     * @throws ReservedCustomTaxonomyNameFormat
     */
    public static function register(): void
    {
        Bootstrapper::registerEntities();
    }
}
