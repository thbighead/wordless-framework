<?php

namespace Wordless\Contracts\Controller;

use Wordless\Adapters\Request;
use Wordless\Adapters\Response;

trait RestingWordPress
{
    /** @inheritDoc */
    public function create_item($request)
    {
        return $this->store(Request::fromWpRestRequest($request))->respond();
    }

    /** @inheritDoc */
    public function delete_item($request)
    {
        return $this->destroy(Request::fromWpRestRequest($request))->respond();
    }

    /**
     * @param Request $request
     * @return Response
     */
    public function destroy(Request $request): Response
    {
        return $this->mountNotImplementedError($request);
    }

    /** @inheritDoc */
    public function get_item($request)
    {
        return $this->show(Request::fromWpRestRequest($request))->respond();
    }

    /** @inheritDoc */
    public function get_items($request)
    {
        return $this->index(Request::fromWpRestRequest($request))->respond();
    }

    /**
     * @param Request $request
     * @return Response
     */
    public function index(Request $request): Response
    {
        return $this->mountNotImplementedError($request);
    }

    /**
     * @param Request $request
     * @return Response
     */
    public function show(Request $request): Response
    {
        return $this->mountNotImplementedError($request);
    }

    /**
     * @param Request $request
     * @return Response
     */
    public function store(Request $request): Response
    {
        return $this->mountNotImplementedError($request);
    }

    /**
     * @param Request $request
     * @return Response
     */
    public function update(Request $request): Response
    {
        return $this->mountNotImplementedError($request);
    }

    /** @inheritDoc */
    public function update_item($request)
    {
        return $this->update(Request::fromWpRestRequest($request))->respond();
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
