<?php
declare(strict_types=1);

namespace Stack\Fest\Config;

use ReflectionClass;
use ReflectionException;

class Router
{
    private array $controllers = [];

    public function controllers(array $controllers = [])
    {
        $this->controllers = $controllers;
        $this->run();
    }

    /**
     * @throws ReflectionException
     */
    public function run()
    {
        $acessedRoute = $this->getAccessedRoute();

        foreach ($this->controllers as $controller) {
            $reflectionController = new ReflectionClass($controller);
            foreach ($reflectionController->getMethods() as $method) {
                foreach ($method->getAttributes() as $route) {
                    $routeInstance = $route->newInstance();
                    if ($routeInstance->isMatch($acessedRoute)) {
                        return (new Controller($controller))->runMethod(
                            $method->getName(),
                            $routeInstance->getDynamicParameters($acessedRoute)
                        );
                    }
                }
            }
        }
    }

    public function getAccessedRoute()
    {
        global $argv;
        $route = $this->isCli() ? $argv[1] : $_SERVER['REQUEST_URI'];
        $method = $this->isCli() ? "GET" : $_SERVER['REQUEST_METHOD'];

        return new Route($route, $method);
    }

    public function isCli()
    {
        return php_sapi_name() === 'cli';
    }
}
