<?php

namespace ZborovoSK\ZBFCore\Router;

use ZborovoSK\ZBFCore\Http\Request;


class Route
{
    private string $method = 'GET';
    private string $path;
    private string $pathRegex;
    private string $controllerClass;
    private string $actionName;

    public function __construct(string $method, string $path, string $controllerClass, string $actionName)
    {
        $this->method = strtolower($method);
        $this->path = $path;
        $this->controllerClass = $controllerClass;
        $this->actionName = $actionName;
        $this->pathRegex = $this->createRegexFromPath($path);
    }

    private function createRegexFromPath(string $path): string
    {
        //escape / to \/
        $pathRegex = str_replace('/', '\/', $path);

        //replace {param} with named regex group
        $pathRegex = preg_replace('/{([a-zA-Z0-9]+)}/', '(?P<$1>[a-zA-Z0-9\.\-]+)', $pathRegex);

        //return regex
        return '/^' . $pathRegex . '$/';
    }

    public function match(string $path, Request $request): bool
    {
        //first checko if method is same

        if (
            $this->method !== strtolower($request->getMethod()) &&
            !in_array($this->method, ['all', '*'])
        ) {
            return false;
        }

        if (preg_match($this->pathRegex, $path, $matches)) {

            //remove not named keys
            $params = [];
            foreach ($matches as $key => $value) {
                if (is_string($key)) {
                    $params[$key] = $value;
                }
            }

            //set params to request
            $request->setParams($params);

            //set controller class and action name to request
            $request->setControllerClass($this->controllerClass);
            $request->setActionName($this->actionName);

            //return true because path matched
            return true;
        }

        //return false because path not matched
        return false;
    }
}
