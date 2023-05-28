<?php

namespace Wordless\Infrastructure\Wordpress\ApiController\Traits;

use Wordless\Infrastructure\Http\Request;

trait Routing
{
    public function register_routes()
    {
        $this->registerDestroyRoute();
        $this->registerIndexRoute();
        $this->registerShowRoute();
        $this->registerStoreRoute();
        $this->registerUpdateRoute();
    }

    public function registerDestroyRoute()
    {
        $this->routeBaseRegistration([
            'methods' => Request::HTTP_DELETE,
            'callback' => [$this, self::METHOD_NAME_TO_REST_DESTROY_ITEM],
            'permission_callback' => [$this, self::PERMISSION_METHOD_NAME_TO_REST_DESTROY_ITEM],
            'args' => [
                'force' => [
                    'type' => 'boolean',
                    'default' => false,
                    'description' => __('Whether to bypass Trash and force deletion.'),
                ],
            ],
        ], $this->defineCustomRestBaseWithIdRouteParameter());
    }

    public function registerIndexRoute()
    {
        $this->routeBaseRegistration([
            'methods' => Request::HTTP_GET,
            'callback' => [$this, self::METHOD_NAME_TO_REST_INDEX_ITEMS],
            'permission_callback' => [$this, self::PERMISSION_METHOD_NAME_TO_REST_INDEX_ITEMS],
            'args' => $this->get_collection_params(),
        ]);
    }

    public function registerShowRoute()
    {
        $this->routeBaseRegistration([
            'methods' => Request::HTTP_GET,
            'callback' => [$this, self::METHOD_NAME_TO_REST_SHOW_ITEM],
            'permission_callback' => [$this, self::PERMISSION_METHOD_NAME_TO_REST_SHOW_ITEM],
            'args' => [
                'context' => $this->get_context_param(['default' => 'view']),
            ],
        ], $this->defineCustomRestBaseWithIdRouteParameter());
    }

    public function registerStoreRoute()
    {
        $this->routeBaseRegistration([
            'methods' => Request::HTTP_POST,
            'callback' => [$this, self::METHOD_NAME_TO_REST_STORE_ITEM],
            'permission_callback' => [$this, self::PERMISSION_METHOD_NAME_TO_REST_STORE_ITEM],
            'args' => $this->get_collection_params(),
        ]);
    }

    public function registerUpdateRoute()
    {
        $this->routeBaseRegistration([
            'methods' => Request::EDITABLE,
            'callback' => [$this, self::METHOD_NAME_TO_REST_UPDATE_ITEM],
            'permission_callback' => [$this, self::PERMISSION_METHOD_NAME_TO_REST_UPDATE_ITEM],
            'args' => $this->get_endpoint_args_for_item_schema(Request::EDITABLE),
        ], $this->defineCustomRestBaseWithIdRouteParameter());
    }

    protected function routeBaseRegistration(
        array   $route_details,
        ?string $custom_rest_base = null,
        ?string $custom_namespace = null
    )
    {
        register_rest_route(
            $custom_namespace ?? $this->namespace,
            $custom_rest_base ?? "/$this->rest_base",
            $route_details
        );
    }

    private function defineCustomRestBaseWithIdRouteParameter(): string
    {
        return "/$this->rest_base/(?P<id>[\d]+)";
    }
}
