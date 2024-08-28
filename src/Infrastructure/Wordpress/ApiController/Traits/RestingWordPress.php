<?php declare(strict_types=1);

namespace Wordless\Infrastructure\Wordpress\ApiController\Traits;

use InvalidArgumentException;
use Wordless\Application\Helpers\Debugger;
use Wordless\Infrastructure\Http\Response\Enums\StatusCode;
use Wordless\Infrastructure\Wordpress\ApiController\Request;
use Wordless\Infrastructure\Wordpress\ApiController\Response;
use Wordless\Infrastructure\Wordpress\ApiController\Traits\ResourceValidation\Exceptions\ValidationError;
use WP_Error;
use WP_REST_Request;

trait RestingWordPress
{
    /**
     * @param WP_REST_Request $request
     * @return Response|WP_Error
     * @throws InvalidArgumentException
     */
    final public function create_item($request): Response|WP_Error
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
     * @throws InvalidArgumentException
     */
    final public function delete_item($request): Response|WP_Error
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
     * @throws InvalidArgumentException
     */
    public function destroy(Request $request): Response
    {
        return $this->mountNotImplementedError($request);
    }

    /**
     * @param WP_REST_Request $request
     * @return Response|WP_Error
     * @throws InvalidArgumentException
     */
    final public function get_item($request): Response|WP_Error
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
     * @throws InvalidArgumentException
     */
    final public function get_items($request): Response|WP_Error
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
     * @throws InvalidArgumentException
     */
    public function index(Request $request): Response
    {
        return $this->mountNotImplementedError($request);
    }

    /**
     * @param Request $request
     * @return Response
     * @throws InvalidArgumentException
     */
    public function show(Request $request): Response
    {
        return $this->mountNotImplementedError($request);
    }

    /**
     * @param Request $request
     * @return Response
     * @throws InvalidArgumentException
     */
    public function store(Request $request): Response
    {
        return $this->mountNotImplementedError($request);
    }

    /**
     * @param Request $request
     * @return Response
     * @throws InvalidArgumentException
     */
    public function update(Request $request): Response
    {
        return $this->mountNotImplementedError($request);
    }

    /**
     * @param WP_REST_Request $request
     * @return Response|WP_Error
     * @throws InvalidArgumentException
     */
    final public function update_item($request): Response|WP_Error
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

    /**
     * @param Request $request
     * @return Response
     * @throws InvalidArgumentException
     */
    private function mountNotImplementedError(Request $request): Response
    {
        return Response::error(
            StatusCode::method_not_allowed_405,
            sprintf(
                __('Method \'%s\' not implemented. Must be overridden in subclass.'),
                Debugger::calledFrom()
            ),
            $request->get_params()
        );
    }
}
