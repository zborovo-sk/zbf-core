<?php

namespace ZborovoSK\ZBFCore\Router;

use ZborovoSK\ZBFCore\Http\Request;
use ZborovoSK\ZBFCore\ZBFException;
use ZborovoSK\ZBFCore\Router\Route;


class Router
{
    /**
     * @var Route[]
     */
    private array $routes = [];

    public function addRoute(Route $route): void
    {
        $this->routes[] = $route;
    }

    public function hasMatch(Request $request): bool
    {
        foreach ($this->routes as $route) {
            if ($route->match($request->getPath(), $request)) {
                return true;
            }
        }

        return false;
    }
}
