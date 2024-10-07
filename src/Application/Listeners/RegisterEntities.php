<?php

namespace Wordless\Application\Listeners;

use InvalidArgumentException;
use Symfony\Component\Dotenv\Exception\FormatException;
use Wordless\Application\Helpers\Config\Contracts\Subjectable\DTO\ConfigSubjectDTO\Exceptions\EmptyConfigKey;
use Wordless\Application\Helpers\ProjectPath\Exceptions\PathNotFoundException;
use Wordless\Core\Bootstrapper;
use Wordless\Core\Bootstrapper\Exceptions\InvalidProviderClass;
use Wordless\Core\Exceptions\DotEnvNotSetException;
use Wordless\Infrastructure\Wordpress\CustomPost\Traits\Register\Exceptions\CustomPostTypeRegistrationFailed;
use Wordless\Infrastructure\Wordpress\CustomPost\Traits\Register\Traits\Validation\Exceptions\InvalidCustomPostTypeKey;
use Wordless\Infrastructure\Wordpress\CustomPost\Traits\Register\Traits\Validation\Exceptions\ReservedCustomPostTypeKey;
use Wordless\Infrastructure\Wordpress\CustomPostStatus\Traits\Register\Traits\Validation\Exceptions\ReservedCustomPostStatusKey;
use Wordless\Infrastructure\Wordpress\Hook\Contracts\ActionHook;
use Wordless\Infrastructure\Wordpress\Listener\ActionListener;
use Wordless\Infrastructure\Wordpress\Taxonomy\CustomTaxonomy\Traits\Register\Exceptions\CustomTaxonomyRegistrationFailed;
use Wordless\Infrastructure\Wordpress\Taxonomy\CustomTaxonomy\Traits\Register\Exceptions\InvalidObjectTypeAssociationToTaxonomy;
use Wordless\Infrastructure\Wordpress\Taxonomy\CustomTaxonomy\Traits\Register\Validation\Exceptions\InvalidCustomTaxonomyName;
use Wordless\Infrastructure\Wordpress\Taxonomy\CustomTaxonomy\Traits\Register\Validation\Exceptions\ReservedCustomTaxonomyName;
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
     * @throws InvalidCustomPostTypeKey
     * @throws InvalidCustomTaxonomyName
     * @throws InvalidObjectTypeAssociationToTaxonomy
     * @throws InvalidProviderClass
     * @throws PathNotFoundException
     * @throws ReservedCustomPostStatusKey
     * @throws ReservedCustomPostTypeKey
     * @throws ReservedCustomTaxonomyName
     */
    public static function register(): void
    {
        Bootstrapper::registerEntities();
    }
}
