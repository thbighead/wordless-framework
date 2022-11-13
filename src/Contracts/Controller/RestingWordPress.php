<?php

namespace Wordless\Contracts\Controller;

use Wordless\Adapters\Request;
use Wordless\Adapters\Response;

trait RestingWordPress
{
    /** @inheritDoc */
    public function create_item($request)
    {
        /** @var Request $requestClass */
        $requestClass = static::STORE_REQUEST_CLASS;

        return $this->store($requestClass::fromWpRestRequest($request))->respond();
    }

    /** @inheritDoc */
    public function delete_item($request)
    {
        /** @var Request $requestClass */
        $requestClass = static::DESTROY_REQUEST_CLASS;

        return $this->destroy($requestClass::fromWpRestRequest($request))->respond();
    }

    public function destroy(Request $request): Response
    {
        return $this->mountNotImplementedError($request);
    }

    /** @inheritDoc */
    public function get_item($request)
    {
        /** @var Request $requestClass */
        $requestClass = static::SHOW_REQUEST_CLASS;

        return $this->show($requestClass::fromWpRestRequest($request))->respond();
    }

    /** @inheritDoc */
    public function get_items($request)
    {
        /** @var Request $requestClass */
        $requestClass = static::INDEX_REQUEST_CLASS;

        return $this->index($requestClass::fromWpRestRequest($request))->respond();
    }

    public function index(Request $request): Response
    {
        return $this->mountNotImplementedError($request);
    }

    public function show(Request $request): Response
    {
        return $this->mountNotImplementedError($request);
    }

    public function store(Request $request): Response
    {
        return $this->mountNotImplementedError($request);
    }

    public function update(Request $request): Response
    {
        return $this->mountNotImplementedError($request);
    }

    /** @inheritDoc */
    public function update_item($request)
    {
        /** @var Request $requestClass */
        $requestClass = static::UPDATE_REQUEST_CLASS;

        return $this->update($requestClass::fromWpRestRequest($request))->respond();
    }

    private function mountNotImplementedError(Request $request): Response
    {
        return Response::error(
            Response::HTTP_405_METHOD_NOT_ALLOWED,
            sprintf(__('Method \'%s\' not implemented. Must be overridden in subclass.'), __METHOD__),
            $request->get_params()
        );
    }
}
