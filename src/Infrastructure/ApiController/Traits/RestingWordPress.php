<?php

namespace Wordless\Controller\Traits;

use Wordless\Application\Helpers\Debugger;
use Wordless\Exceptions\ValidationError;
use Wordless\Infrastructure\Http\Request;
use Wordless\Infrastructure\Http\Response;
use WP_Error;
use WP_REST_Request;

trait RestingWordPress
{
    /**
     * @param WP_REST_Request $request
     * @return Response|WP_Error
     */
    final public function create_item($request)
    {
        try {
            return $this->store(Request::fromWpRestRequest(
                $request,
                $this->validateArguments($this->validateResourceStore(), $request->get_params())
            ))->respond();
        } catch (ValidationError $exception) {
            return $exception->getResponse()->respond();
        }
    }

    /**
     * @param WP_REST_Request $request
     * @return Response|WP_Error
     */
    final public function delete_item($request)
    {
        try {
            return $this->destroy(Request::fromWpRestRequest(
                $request,
                $this->validateArguments($this->validateResourceDestroy(), $request->get_params())
            ))->respond();
        } catch (ValidationError $exception) {
            return $exception->getResponse()->respond();
        }
    }

    /**
     * @param Request $request
     * @return Response
     */
    public function destroy(Request $request): Response
    {
        return $this->mountNotImplementedError($request);
    }

    /**
     * @param WP_REST_Request $request
     * @return Response|WP_Error
     */
    final public function get_item($request)
    {
        try {
            return $this->show(Request::fromWpRestRequest(
                $request,
                $this->validateArguments($this->validateResourceShow(), $request->get_params())
            ))->respond();
        } catch (ValidationError $exception) {
            return $exception->getResponse()->respond();
        }
    }

    /**
     * @param WP_REST_Request $request
     * @return Response|WP_Error
     */
    final public function get_items($request)
    {
        try {
            return $this->index(Request::fromWpRestRequest(
                $request,
                $this->validateArguments($this->validateResourceIndex(), $request->get_params())
            ))->respond();
        } catch (ValidationError $exception) {
            return $exception->getResponse()->respond();
        }
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

    /**
     * @param WP_REST_Request $request
     * @return Response|WP_Error
     */
    final public function update_item($request)
    {
        try {
            return $this->update(Request::fromWpRestRequest(
                $request,
                $this->validateArguments($this->validateResourceUpdate(), $request->get_params())
            ))->respond();
        } catch (ValidationError $exception) {
            return $exception->getResponse()->respond();
        }
    }

    private function mountNotImplementedError(Request $request): Response
    {
        return Response::error(
            Response::HTTP_404_NOT_FOUND,
            sprintf(
                __('Method \'%s\' not implemented. Must be overridden in subclass.'),
                Debugger::calledFrom()
            ),
            $request->get_params()
        );
    }
}
