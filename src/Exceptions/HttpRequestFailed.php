<?php

namespace Wordless\Exceptions;

use Exception;
use Throwable;
use WP_Error;

class HttpRequestFailed extends Exception
{
    private WP_Error $wpError;

    public function __construct(WP_Error $requestError, Throwable $previous = null)
    {
        $this->wpError = $requestError;

        $errors = [
            'messages' => $this->wpError->get_error_messages(),
            'response' => $this->wpError->get_all_error_data(),
        ];

        parent::__construct(
            json_encode($errors, JSON_PRETTY_PRINT),
            0,
            $previous
        );
    }

    public function getWpError(): WP_Error
    {
        return $this->wpError;
    }
}
