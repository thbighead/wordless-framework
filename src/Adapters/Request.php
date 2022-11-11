<?php

namespace Wordless\Adapters;

use Symfony\Component\HttpFoundation\Request as SymfonyRequest;
use WP_REST_Request;

class Request extends WP_REST_Request
{
    public const EDITABLE = 'PUT, PATCH';
    public const HTTP_CONNECT = SymfonyRequest::METHOD_CONNECT;
    public const HTTP_DELETE = SymfonyRequest::METHOD_DELETE;
    public const HTTP_GET = SymfonyRequest::METHOD_GET;
    public const HTTP_HEAD = SymfonyRequest::METHOD_HEAD;
    public const HTTP_OPTIONS = SymfonyRequest::METHOD_OPTIONS;
    public const HTTP_PATCH = SymfonyRequest::METHOD_PATCH;
    public const HTTP_POST = SymfonyRequest::METHOD_POST;
    public const HTTP_PUT = SymfonyRequest::METHOD_PUT;
    public const HTTP_PURGE = SymfonyRequest::METHOD_PURGE;
    public const HTTP_TRACE = SymfonyRequest::METHOD_TRACE;
}
