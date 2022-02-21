<?php

namespace Wordless\Abstractions;

use Wordless\Exception\FailedToChangeTimezone;
use Wordless\Helpers\Environment;

class Timezone
{
    public const WP_TIMEZONE_OPTION_NAME = 'timezone_string';

    private string $timezone;
    private string $php_timezone;
    private ?string $wp_timezone;

    public function __construct(?string $timezone = null)
    {
        $this->wp_timezone = null;
        $this->php_timezone = $this->timezone = $timezone ?? date_default_timezone_get();

        if ($env_timezone = Environment::get('TIMEZONE')) {
            $this->php_timezone = $this->timezone = $env_timezone;
        }

        if (function_exists('get_option') && ($wp_timezone = get_option(self::WP_TIMEZONE_OPTION_NAME))) {
            $this->wp_timezone = $this->timezone = $wp_timezone;
        }

        if ($timezone) {
            $this->timezone = $timezone;
        }
    }

    /**
     * @return void
     * @throws FailedToChangeTimezone
     */
    public function setDefaultTimezone()
    {
        if (date_default_timezone_set($this->timezone) === false) {
            throw new FailedToChangeTimezone($this->timezone);
        }
    }

    /**
     * @return void
     * @throws FailedToChangeTimezone
     */
    public function setDefaultTimezoneAsPhp()
    {
        if (date_default_timezone_set($this->php_timezone) === false) {
            throw new FailedToChangeTimezone($this->php_timezone);
        }
    }

    /**
     * @return void
     * @throws FailedToChangeTimezone
     */
    public function setDefaultTimezoneAsWp()
    {
        if (date_default_timezone_set($this->wp_timezone) === false) {
            throw new FailedToChangeTimezone("$this->wp_timezone");
        }
    }
}