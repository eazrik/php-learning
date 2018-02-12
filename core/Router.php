<?php

class Router
{
    protected $routes = [
        'GET' => [],
        'POST' => []
    ];

    public static function load($file)
    {
        $router = new static;

        require $file;

        return $router;
    }

    public function get($uri, $controllers)
    {
        $this->routes['GET'][$uri] = $controllers;
    }

    public function post($uri, $controllers)
    {
        $this->routes['POST'][$uri] = $controllers;
    }

    public function direct($uri, $requestType)
    {
        if (array_key_exists($uri, $this->routes[$requestType])) {
            return $this->callAction(
                ...explode('@', $this->routes[$requestType][$uri])
            );
        }

        throw new Exception("No routes define for this URI");
    }

    protected function callAction($controller, $action)
    {
        $controller = new $controller;

        if (! method_exists($controller, $action)) {
            throw new Exception(
                "{$controller} dows not respond to the {$action} action."
            );
        }

        return $controller->$action();
    }
}
