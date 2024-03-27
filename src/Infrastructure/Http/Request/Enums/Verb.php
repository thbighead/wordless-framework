<?php declare(strict_types=1);

namespace Wordless\Infrastructure\Http\Request\Enums;

use Symfony\Component\HttpFoundation\Request;

enum Verb: string
{
    public const EDITABLE = 'PUT, PATCH';

    case connect = Request::METHOD_CONNECT;
    case delete = Request::METHOD_DELETE;
    case get = Request::METHOD_GET;
    case head = Request::METHOD_HEAD;
    case options = Request::METHOD_OPTIONS;
    case patch = Request::METHOD_PATCH;
    case post = Request::METHOD_POST;
    case put = Request::METHOD_PUT;
    case purge = Request::METHOD_PURGE;
    case trace = Request::METHOD_TRACE;
}
