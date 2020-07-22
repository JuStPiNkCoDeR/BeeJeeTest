<?php


class Route
{
    /**
     *
     */
    static function init()
    {
        $controllerName = 'index';
        $actionName = 'index';
        // Get URI without get query
        $routeString = explode('?', $_SERVER['REQUEST_URI'])[0];
        $routeParts = explode('/', $routeString);
        $actionPartIndex = sizeof($routeParts) - 1;
        $controllerPartIndex = $actionPartIndex - 1;

        if (isset($routeParts[$controllerPartIndex]) && !empty($routeParts[$controllerPartIndex])) {
            $controllerName = $routeParts[$controllerPartIndex];
        }

        if (isset($routeParts[$actionPartIndex]) && !empty($routeParts[$actionPartIndex])) {
            $actionName = $routeParts[$actionPartIndex];
        }

        $controllerClassName = 'Controller_' . $controllerName;
        $actionMethodName = 'action_' . $actionName;

        $controller = new $controllerClassName;

        if (method_exists($controller, $actionMethodName)) {
            $controller->$actionMethodName($_GET, $_POST);
        } else {
            echo 404;
        }
    }
}