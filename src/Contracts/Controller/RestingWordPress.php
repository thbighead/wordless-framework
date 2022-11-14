<?php

namespace Wordless\Contracts\Controller;

use Wordless\Adapters\AbstractValidatedRequest;
use Wordless\Adapters\Request;
use Wordless\Adapters\Response;

trait RestingWordPress
{
    /** @inheritDoc */
    public function create_item($request)
    {
        return $this->store($this->request)->respond();
    }

    /** @inheritDoc */
    public function delete_item($request)
    {
        return $this->destroy($this->request)->respond();
    }

    /**
     * @param Request|AbstractValidatedRequest $request
     * @return Response
     */
    public function destroy($request): Response
    {
        return $this->mountNotImplementedError($request);
    }

    /** @inheritDoc */
    public function get_item($request)
    {
        return $this->show($this->request)->respond();
    }

    /** @inheritDoc */
    public function get_items($request)
    {
        return $this->index($this->request)->respond();
    }

    /**
     * @param Request|AbstractValidatedRequest $request
     * @return Response
     */
    public function index($request): Response
    {
        return $this->mountNotImplementedError($request);
    }

    /**
     * @param Request|AbstractValidatedRequest $request
     * @return Response
     */
    public function show($request): Response
    {
        return $this->mountNotImplementedError($request);
    }

    /**
     * @param Request|AbstractValidatedRequest $request
     * @return Response
     */
    public function store($request): Response
    {
        return $this->mountNotImplementedError($request);
    }

    /**
     * @param Request|AbstractValidatedRequest $request
     * @return Response
     */
    public function update($request): Response
    {
        return $this->mountNotImplementedError($request);
    }

    /** @inheritDoc */
    public function update_item($request)
    {
        return $this->update($this->request)->respond();
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
