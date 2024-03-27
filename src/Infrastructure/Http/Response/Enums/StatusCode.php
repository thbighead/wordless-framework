<?php declare(strict_types=1);

namespace Wordless\Infrastructure\Http\Response\Enums;

use InvalidArgumentException;
use Symfony\Component\HttpFoundation\Response;
use Wordless\Application\Helpers\Str;

enum StatusCode: int
{
    case continue_100 = Response::HTTP_CONTINUE;
    case switching_protocols_101 = Response::HTTP_SWITCHING_PROTOCOLS;
    case processing_102 = Response::HTTP_PROCESSING; // RFC2518
    case early_hints_103 = Response::HTTP_EARLY_HINTS;
    case ok_200 = Response::HTTP_OK;
    case created_201 = Response::HTTP_CREATED;
    case accepted_202 = Response::HTTP_ACCEPTED;
    case non_authoritative_information_203 = Response::HTTP_NON_AUTHORITATIVE_INFORMATION;
    case no_content_204 = Response::HTTP_NO_CONTENT;
    case reset_content_205 = Response::HTTP_RESET_CONTENT;
    case partial_content_206 = Response::HTTP_PARTIAL_CONTENT;
    case multi_status_207 = Response::HTTP_MULTI_STATUS; // RFC4918
    case already_reported_208 = Response::HTTP_ALREADY_REPORTED; // RFC5842
    case im_used_226 = Response::HTTP_IM_USED; // RFC3229
    case multiple_choices_300 = Response::HTTP_MULTIPLE_CHOICES;
    case moved_permanently_301 = Response::HTTP_MOVED_PERMANENTLY;
    case found_302 = Response::HTTP_FOUND;
    case see_other_303 = Response::HTTP_SEE_OTHER;
    case not_modified_304 = Response::HTTP_NOT_MODIFIED;
    case use_proxy_305 = Response::HTTP_USE_PROXY;
    case reserved_306 = Response::HTTP_RESERVED;
    case temporary_redirect_307 = Response::HTTP_TEMPORARY_REDIRECT;
    case permanently_redirect_308 = Response::HTTP_PERMANENTLY_REDIRECT; // RFC7238
    case bad_request_400 = Response::HTTP_BAD_REQUEST;
    case unauthorized_401 = Response::HTTP_UNAUTHORIZED;
    case payment_required_402 = Response::HTTP_PAYMENT_REQUIRED;
    case forbidden_403 = Response::HTTP_FORBIDDEN;
    case not_found_404 = Response::HTTP_NOT_FOUND;
    case method_not_allowed_405 = Response::HTTP_METHOD_NOT_ALLOWED;
    case not_acceptable_406 = Response::HTTP_NOT_ACCEPTABLE;
    case proxy_authentication_required_407 = Response::HTTP_PROXY_AUTHENTICATION_REQUIRED;
    case request_timeout_408 = Response::HTTP_REQUEST_TIMEOUT;
    case conflict_409 = Response::HTTP_CONFLICT;
    case gone_410 = Response::HTTP_GONE;
    case length_required_411 = Response::HTTP_LENGTH_REQUIRED;
    case precondition_failed_412 = Response::HTTP_PRECONDITION_FAILED;
    case request_entity_too_large_413 = Response::HTTP_REQUEST_ENTITY_TOO_LARGE; // RFC-ietf-httpbis-semantics
    case request_uri_too_long_414 = Response::HTTP_REQUEST_URI_TOO_LONG;
    case unsupported_media_type_415 = Response::HTTP_UNSUPPORTED_MEDIA_TYPE;
    case requested_range_not_satisfiable_416 = Response::HTTP_REQUESTED_RANGE_NOT_SATISFIABLE;
    case expectation_failed_417 = Response::HTTP_EXPECTATION_FAILED;
    case i_am_a_teapot_418 = Response::HTTP_I_AM_A_TEAPOT; // RFC2324
    case misdirected_request_421 = Response::HTTP_MISDIRECTED_REQUEST; // RFC7540
    case unprocessable_entity_422 = Response::HTTP_UNPROCESSABLE_ENTITY; // RFC-ietf-httpbis-semantics
    case locked_423 = Response::HTTP_LOCKED; // RFC4918
    case failed_dependency_424 = Response::HTTP_FAILED_DEPENDENCY; // RFC4918
    case too_early_425 = Response::HTTP_TOO_EARLY; // RFC-ietf-httpbis-replay-04
    case upgrade_required_426 = Response::HTTP_UPGRADE_REQUIRED; // RFC2817
    case precondition_required_428 = Response::HTTP_PRECONDITION_REQUIRED; // RFC6585
    case too_many_requests_429 = Response::HTTP_TOO_MANY_REQUESTS; // RFC6585
    case request_header_fields_too_large_431 = Response::HTTP_REQUEST_HEADER_FIELDS_TOO_LARGE; // RFC6585
    case unavailable_for_legal_reasons_451 = Response::HTTP_UNAVAILABLE_FOR_LEGAL_REASONS; // RFC7725
    case internal_server_error_500 = Response::HTTP_INTERNAL_SERVER_ERROR;
    case unot_implemented_501 = Response::HTTP_NOT_IMPLEMENTED;
    case bad_gateway_502 = Response::HTTP_BAD_GATEWAY;
    case service_unavailable_503 = Response::HTTP_SERVICE_UNAVAILABLE;
    case gateway_timeout_504 = Response::HTTP_GATEWAY_TIMEOUT;
    case version_not_supported_505 = Response::HTTP_VERSION_NOT_SUPPORTED;
    case variant_also_negotiates_experimental_506 = Response::HTTP_VARIANT_ALSO_NEGOTIATES_EXPERIMENTAL; // RFC2295
    case insufficient_storage_507 = Response::HTTP_INSUFFICIENT_STORAGE; // RFC4918
    case loop_detected_508 = Response::HTTP_LOOP_DETECTED; // RFC5842
    case not_extended_510 = Response::HTTP_NOT_EXTENDED; // RFC2774
    case network_authentication_required_511 = Response::HTTP_NETWORK_AUTHENTICATION_REQUIRED; // RFC6585

    /**
     * @return string
     * @throws InvalidArgumentException
     */
    public function asText(): string
    {
        return (string)Str::of($this->name)->beforeLast('_')->titleCase();
    }
}
