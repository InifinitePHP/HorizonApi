<?php

use Symfony\Component\Routing\Loader\Configurator\RoutingConfigurator;
use App\Api\Auth\UserController;

return function (RoutingConfigurator $routes) {

    $controller = UserController::class;
    $actions = $controller::actions();

    $version = $controller::$version;
    $prefix = $controller::$route;

    $base_url = "/api/$version/$prefix";

    foreach ($actions as $action) {
        
        $name = "{$prefix}_$action->name";
        $url = $base_url . $action->path;

        $routes->add($name, $url)
            ->controller([$controller, $action->name])
            ->methods($action->methods);
    }
};
