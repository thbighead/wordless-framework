<?php

namespace Wordless\Infrastructure\Http;

use Symfony\Component\HttpFoundation\Response as SymfonyResponse;
use Wordless\Application\Helpers\Str;
use WP_Error;
use WP_REST_Response;

class Response extends WP_REST_Response implements HeaderBag
{
    public const HTTP_100_CONTINUE = SymfonyResponse::HTTP_CONTINUE;
    public const HTTP_101_SWITCHING_PROTOCOLS = SymfonyResponse::HTTP_SWITCHING_PROTOCOLS;
    public const HTTP_102_PROCESSING = SymfonyResponse::HTTP_PROCESSING;
    public const HTTP_103_EARLY_HINTS = SymfonyResponse::HTTP_EARLY_HINTS;
    public const HTTP_200_OK = SymfonyResponse::HTTP_OK;
    public const HTTP_201_CREATED = SymfonyResponse::HTTP_CREATED;
    public const HTTP_202_ACCEPTED = SymfonyResponse::HTTP_ACCEPTED;
    public const HTTP_203_NON_AUTHORITATIVE_INFORMATION = SymfonyResponse::HTTP_NON_AUTHORITATIVE_INFORMATION;
    public const HTTP_204_NO_CONTENT = SymfonyResponse::HTTP_NO_CONTENT;
    public const HTTP_205_RESET_CONTENT = SymfonyResponse::HTTP_RESET_CONTENT;
    public const HTTP_206_PARTIAL_CONTENT = SymfonyResponse::HTTP_PARTIAL_CONTENT;
    public const HTTP_207_MULTI_STATUS = SymfonyResponse::HTTP_MULTI_STATUS;
    public const HTTP_208_ALREADY_REPORTED = SymfonyResponse::HTTP_ALREADY_REPORTED;
    public const HTTP_226_IM_USED = SymfonyResponse::HTTP_IM_USED;
    public const HTTP_300_MULTIPLE_CHOICES = SymfonyResponse::HTTP_MULTIPLE_CHOICES;
    public const HTTP_301_MOVED_PERMANENTLY = SymfonyResponse::HTTP_MOVED_PERMANENTLY;
    public const HTTP_302_FOUND = SymfonyResponse::HTTP_FOUND;
    public const HTTP_303_SEE_OTHER = SymfonyResponse::HTTP_SEE_OTHER;
    public const HTTP_304_NOT_MODIFIED = SymfonyResponse::HTTP_NOT_MODIFIED;
    public const HTTP_305_USE_PROXY = SymfonyResponse::HTTP_USE_PROXY;
    public const HTTP_306_RESERVED = SymfonyResponse::HTTP_RESERVED;
    public const HTTP_307_TEMPORARY_REDIRECT = SymfonyResponse::HTTP_TEMPORARY_REDIRECT;
    public const HTTP_308_PERMANENTLY_REDIRECT = SymfonyResponse::HTTP_PERMANENTLY_REDIRECT;
    public const HTTP_400_BAD_REQUEST = SymfonyResponse::HTTP_BAD_REQUEST;
    public const HTTP_401_UNAUTHORIZED = SymfonyResponse::HTTP_UNAUTHORIZED;
    public const HTTP_402_PAYMENT_REQUIRED = SymfonyResponse::HTTP_PAYMENT_REQUIRED;
    public const HTTP_403_FORBIDDEN = SymfonyResponse::HTTP_FORBIDDEN;
    public const HTTP_404_NOT_FOUND = SymfonyResponse::HTTP_NOT_FOUND;
    public const HTTP_405_METHOD_NOT_ALLOWED = SymfonyResponse::HTTP_METHOD_NOT_ALLOWED;
    public const HTTP_406_NOT_ACCEPTABLE = SymfonyResponse::HTTP_NOT_ACCEPTABLE;
    public const HTTP_407_PROXY_AUTHENTICATION_REQUIRED = SymfonyResponse::HTTP_PROXY_AUTHENTICATION_REQUIRED;
    public const HTTP_408_REQUEST_TIMEOUT = SymfonyResponse::HTTP_REQUEST_TIMEOUT;
    public const HTTP_409_CONFLICT = SymfonyResponse::HTTP_CONFLICT;
    public const HTTP_410_GONE = SymfonyResponse::HTTP_GONE;
    public const HTTP_411_LENGTH_REQUIRED = SymfonyResponse::HTTP_LENGTH_REQUIRED;
    public const HTTP_412_PRECONDITION_FAILED = SymfonyResponse::HTTP_PRECONDITION_FAILED;
    public const HTTP_413_REQUEST_ENTITY_TOO_LARGE = SymfonyResponse::HTTP_REQUEST_ENTITY_TOO_LARGE;
    public const HTTP_414_REQUEST_URI_TOO_LONG = SymfonyResponse::HTTP_REQUEST_URI_TOO_LONG;
    public const HTTP_415_UNSUPPORTED_MEDIA_TYPE = SymfonyResponse::HTTP_UNSUPPORTED_MEDIA_TYPE;
    public const HTTP_416_REQUESTED_RANGE_NOT_SATISFIABLE = SymfonyResponse::HTTP_REQUESTED_RANGE_NOT_SATISFIABLE;
    public const HTTP_417_EXPECTATION_FAILED = SymfonyResponse::HTTP_EXPECTATION_FAILED;
    public const HTTP_418_I_AM_A_TEAPOT = SymfonyResponse::HTTP_I_AM_A_TEAPOT;
    public const HTTP_421_MISDIRECTED_REQUEST = SymfonyResponse::HTTP_MISDIRECTED_REQUEST;
    public const HTTP_422_UNPROCESSABLE_ENTITY = SymfonyResponse::HTTP_UNPROCESSABLE_ENTITY;
    public const HTTP_423_LOCKED = SymfonyResponse::HTTP_LOCKED;
    public const HTTP_424_FAILED_DEPENDENCY = SymfonyResponse::HTTP_FAILED_DEPENDENCY;
    public const HTTP_425_TOO_EARLY = SymfonyResponse::HTTP_TOO_EARLY;
    public const HTTP_426_UPGRADE_REQUIRED = SymfonyResponse::HTTP_UPGRADE_REQUIRED;
    public const HTTP_428_PRECONDITION_REQUIRED = SymfonyResponse::HTTP_PRECONDITION_REQUIRED;
    public const HTTP_429_TOO_MANY_REQUESTS = SymfonyResponse::HTTP_TOO_MANY_REQUESTS;
    public const HTTP_431_REQUEST_HEADER_FIELDS_TOO_LARGE = SymfonyResponse::HTTP_REQUEST_HEADER_FIELDS_TOO_LARGE;
    public const HTTP_451_UNAVAILABLE_FOR_LEGAL_REASONS = SymfonyResponse::HTTP_UNAVAILABLE_FOR_LEGAL_REASONS;
    public const HTTP_500_INTERNAL_SERVER_ERROR = SymfonyResponse::HTTP_INTERNAL_SERVER_ERROR;
    public const HTTP_501_NOT_IMPLEMENTED = SymfonyResponse::HTTP_NOT_IMPLEMENTED;
    public const HTTP_502_BAD_GATEWAY = SymfonyResponse::HTTP_BAD_GATEWAY;
    public const HTTP_503_SERVICE_UNAVAILABLE = SymfonyResponse::HTTP_SERVICE_UNAVAILABLE;
    public const HTTP_504_GATEWAY_TIMEOUT = SymfonyResponse::HTTP_GATEWAY_TIMEOUT;
    public const HTTP_505_VERSION_NOT_SUPPORTED = SymfonyResponse::HTTP_VERSION_NOT_SUPPORTED;
    public const HTTP_506_VARIANT_ALSO_NEGOTIATES_EXPERIMENTAL =
        SymfonyResponse::HTTP_VARIANT_ALSO_NEGOTIATES_EXPERIMENTAL;
    public const HTTP_507_INSUFFICIENT_STORAGE = SymfonyResponse::HTTP_INSUFFICIENT_STORAGE;
    public const HTTP_508_LOOP_DETECTED = SymfonyResponse::HTTP_LOOP_DETECTED;
    public const HTTP_510_NOT_EXTENDED = SymfonyResponse::HTTP_NOT_EXTENDED;
    public const HTTP_511_NETWORK_AUTHENTICATION_REQUIRED = SymfonyResponse::HTTP_NETWORK_AUTHENTICATION_REQUIRED;
    public const HTTP_STATUS_TEXTS = [
        self::HTTP_100_CONTINUE => 'Continue',
        self::HTTP_101_SWITCHING_PROTOCOLS => 'Switching Protocols',
        self::HTTP_102_PROCESSING => 'Processing', // RFC2518
        self::HTTP_103_EARLY_HINTS => 'Early Hints',
        self::HTTP_200_OK => 'OK',
        self::HTTP_201_CREATED => 'Created',
        self::HTTP_202_ACCEPTED => 'Accepted',
        self::HTTP_203_NON_AUTHORITATIVE_INFORMATION => 'Non-Authoritative Information',
        self::HTTP_204_NO_CONTENT => 'No Content',
        self::HTTP_205_RESET_CONTENT => 'Reset Content',
        self::HTTP_206_PARTIAL_CONTENT => 'Partial Content',
        self::HTTP_207_MULTI_STATUS => 'Multi-Status', // RFC4918
        self::HTTP_208_ALREADY_REPORTED => 'Already Reported', // RFC5842
        self::HTTP_226_IM_USED => 'IM Used', // RFC3229
        self::HTTP_300_MULTIPLE_CHOICES => 'Multiple Choices',
        self::HTTP_301_MOVED_PERMANENTLY => 'Moved Permanently',
        self::HTTP_302_FOUND => 'Found',
        self::HTTP_303_SEE_OTHER => 'See Other',
        self::HTTP_304_NOT_MODIFIED => 'Not Modified',
        self::HTTP_305_USE_PROXY => 'Use Proxy',
        self::HTTP_306_RESERVED => 'Reserved',
        self::HTTP_307_TEMPORARY_REDIRECT => 'Temporary Redirect',
        self::HTTP_308_PERMANENTLY_REDIRECT => 'Permanent Redirect', // RFC7238
        self::HTTP_400_BAD_REQUEST => 'Bad Request',
        self::HTTP_401_UNAUTHORIZED => 'Unauthorized',
        self::HTTP_402_PAYMENT_REQUIRED => 'Payment Required',
        self::HTTP_403_FORBIDDEN => 'Forbidden',
        self::HTTP_404_NOT_FOUND => 'Not Found',
        self::HTTP_405_METHOD_NOT_ALLOWED => 'Method Not Allowed',
        self::HTTP_406_NOT_ACCEPTABLE => 'Not Acceptable',
        self::HTTP_407_PROXY_AUTHENTICATION_REQUIRED => 'Proxy Authentication Required',
        self::HTTP_408_REQUEST_TIMEOUT => 'Request Timeout',
        self::HTTP_409_CONFLICT => 'Conflict',
        self::HTTP_410_GONE => 'Gone',
        self::HTTP_411_LENGTH_REQUIRED => 'Length Required',
        self::HTTP_412_PRECONDITION_FAILED => 'Precondition Failed',
        self::HTTP_413_REQUEST_ENTITY_TOO_LARGE => 'Content Too Large', // RFC-ietf-httpbis-semantics
        self::HTTP_414_REQUEST_URI_TOO_LONG => 'URI Too Long',
        self::HTTP_415_UNSUPPORTED_MEDIA_TYPE => 'Unsupported Media Type',
        self::HTTP_416_REQUESTED_RANGE_NOT_SATISFIABLE => 'Range Not Satisfiable',
        self::HTTP_417_EXPECTATION_FAILED => 'Expectation Failed',
        self::HTTP_418_I_AM_A_TEAPOT => 'I\'m a teapot', // RFC2324
        self::HTTP_421_MISDIRECTED_REQUEST => 'Misdirected Request', // RFC7540
        self::HTTP_422_UNPROCESSABLE_ENTITY => 'Unprocessable Content', // RFC-ietf-httpbis-semantics
        self::HTTP_423_LOCKED => 'Locked', // RFC4918
        self::HTTP_424_FAILED_DEPENDENCY => 'Failed Dependency', // RFC4918
        self::HTTP_425_TOO_EARLY => 'Too Early', // RFC-ietf-httpbis-replay-04
        self::HTTP_426_UPGRADE_REQUIRED => 'Upgrade Required', // RFC2817
        self::HTTP_428_PRECONDITION_REQUIRED => 'Precondition Required', // RFC6585
        self::HTTP_429_TOO_MANY_REQUESTS => 'Too Many Requests', // RFC6585
        self::HTTP_431_REQUEST_HEADER_FIELDS_TOO_LARGE => 'Request Header Fields Too Large', // RFC6585
        self::HTTP_451_UNAVAILABLE_FOR_LEGAL_REASONS => 'Unavailable For Legal Reasons', // RFC7725
        self::HTTP_500_INTERNAL_SERVER_ERROR => 'Internal Server Error',
        self::HTTP_501_NOT_IMPLEMENTED => 'Not Implemented',
        self::HTTP_502_BAD_GATEWAY => 'Bad Gateway',
        self::HTTP_503_SERVICE_UNAVAILABLE => 'Service Unavailable',
        self::HTTP_504_GATEWAY_TIMEOUT => 'Gateway Timeout',
        self::HTTP_505_VERSION_NOT_SUPPORTED => 'HTTP Version Not Supported',
        self::HTTP_506_VARIANT_ALSO_NEGOTIATES_EXPERIMENTAL => 'Variant Also Negotiates', // RFC2295
        self::HTTP_507_INSUFFICIENT_STORAGE => 'Insufficient Storage', // RFC4918
        self::HTTP_508_LOOP_DETECTED => 'Loop Detected', // RFC5842
        self::HTTP_510_NOT_EXTENDED => 'Not Extended', // RFC2774
        self::HTTP_511_NETWORK_AUTHENTICATION_REQUIRED => 'Network Authentication Required', // RFC6585
    ];
    private const DATA_KEY_STATUS = 'status';

    private ?WP_Error $wpError = null;

    public static function canonicalizeHeaderName(string $key): string
    {
        return Str::slugCase($key);
    }

    public static function error(int $http_code, string $message, array $data = []): Response
    {
        return (new static)->setWpError($http_code, $message, $data);
    }

    public function getHeader(string $key, ?string $default = null): ?string
    {
        return $this->headers[self::canonicalizeHeaderName($key)] ?? $default;
    }

    /**
     * @inheritdoc
     */
    public function getHeaders(): array
    {
        return $this->headers;
    }

    public function hasHeader(string $key): bool
    {
        return $this->getHeader($key) !== null;
    }

    public function removeHeader(string $key): Response
    {
        if ($this->hasHeader($key)) {
            unset($this->headers[self::canonicalizeHeaderName($key)]);
        }

        return $this;
    }

    /**
     * @inheritdoc
     */
    public function removeHeaders(array $headers): Response
    {
        foreach ($headers as $header) {
            $this->removeHeader($header);
        }

        return $this;
    }

    public function respond()
    {
        return $this->wpError ?? $this;
    }

    public function setHeader(string $key, string $value, bool $override = false): Response
    {
        $this->header(self::canonicalizeHeaderName($key), $value, $override);

        return $this;
    }

    /**
     * @inheritdoc
     */
    public function setHeaders(array $headers, bool $override = false): Response
    {
        foreach ($headers as $header_key => $header_value) {
            $this->setHeader($header_key, $header_value, $override);
        }

        return $this;
    }

    public function setWpError(int $http_code, string $message, array $data = []): Response
    {
        if (isset($data[self::DATA_KEY_STATUS])) {
            $data['resource_' . self::DATA_KEY_STATUS] = $data[self::DATA_KEY_STATUS];
            unset($data[self::DATA_KEY_STATUS]);
        }

        $this->wpError = new WP_Error(
            self::HTTP_STATUS_TEXTS[$http_code] ?? $http_code,
            $message,
            [self::DATA_KEY_STATUS => $http_code] + $data
        );

        return $this;
    }
}
