<?php
declare(strict_types=1);

namespace Stack\Fest\Config;

use ReflectionClass;

class Controller
{
    private string $classController;
    private Mixed $controller;

    public function __construct(string $classController)
    {
        $this->classController = $classController;
    }

    public function runMethod(string $method, RouteParameters $routeParams): mixed
    {
        if ($this->isValidMethod($method) == false) {
            throw new RuntimeException("Method not found");
        }
        $this->build();
        return $this->controller->$method($routeParams);
    }

    private function isValidMethod(string $method,): bool
    {
        return method_exists($this->classController, $method);
    }

    private function build(): void
    {
        $reflectionControllerInstance = new ReflectionClass($this->classController);
        $this->controller = $this->recursiveDependenciesBuild($reflectionControllerInstance);
    }

    private function recursiveDependenciesBuild(ReflectionClass $reflectionControllerInstance): mixed
    {
        if ($reflectionControllerInstance->getConstructor() == null) {
            return $reflectionControllerInstance->newInstance();
        }

        $dependencies = $reflectionControllerInstance->getConstructor()->getParameters();
        if ($dependencies == null) {
            return $reflectionControllerInstance->newInstance();
        }

        $arguments = array();
        foreach ($dependencies as $dependency) {
            $dependencyClassName = $dependency->getType()->getName();
            $dependencyReflectionClass = new ReflectionClass($dependencyClassName);
            $arguments[] = $this->recursiveDependenciesBuild($dependencyReflectionClass);
        }
        return $reflectionControllerInstance->newInstanceArgs($arguments);
    }
}