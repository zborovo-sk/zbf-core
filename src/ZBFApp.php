<?php

namespace ZborovoSK\ZBFCore;

use ZborovoSK\ZBFCore\Http\Request;
use ZborovoSK\ZBFCore\Http\Context;
use ZborovoSK\ZBFCore\Http\Response;
use ZborovoSK\ZBFCore\Http\ResponseException;

use ZborovoSK\ZBFCore\Router\Router;

use ZborovoSK\ZBFCore\MVC\Controller;
use ZborovoSK\ZBFCore\ZBFException;

class ZBFApp
{
    private static string $webRoot = '/public';
    private static string $appRoot = '/src';

    private Router $router;
    private Request $request;
    private Response $response;
    private Context $context;

    public function __construct(string $webRoot = null, string $appRoot = null)
    {
        if ($webRoot) {
            self::$webRoot = $webRoot;
        }

        if ($appRoot) {
            self::$appRoot = $appRoot;
        }
        $this->router = new Router();
        $this->request = new Request();
        $this->response = new Response();
        $this->context = new Context();
    }

    public static function getWebRoot(): string
    {
        return self::$webRoot;
    }

    public static function getAppRoot(): string
    {
        return self::$appRoot;
    }

    public function getRouter(): Router
    {
        return $this->router;
    }

    public function getRequest(): Request
    {
        return $this->request;
    }

    public function getResponse(): Response
    {
        return $this->response;
    }

    public function getContext(): Context
    {
        return $this->context;
    }

    /**
     * Run current requerst run
     * @throws \ZborovoSK\ZBFCore\Http\ResponseException
     * @return void
     */
    public function run(): void
    {
        if (!$this->router->hasMatch($this->request)) {
            throw new ResponseException(404, ['message' => 'Route not found']);
        }

        //check if controller exists
        $controllerClass = $this->request->getControllerClass();

        if (!class_exists($controllerClass)) {
            throw new ResponseException(500, ['message' => 'Controller not found'], );
        }

        //create controller instance
        try {
            $controller = new $controllerClass($this->request, $this->response, $this->context);
        } catch (ZBFException $e) {
            throw new ResponseException(500, ['message' => 'Could not create controller instance'], $e);
        }

        //check if controller is subclass of Controller
        if (!$controller instanceof Controller) {
            throw new ResponseException(500, ['message' => 'Controller is not subclass of Controller class']);
        }

        //check if method exists
        $actionName = $this->request->getActionName();

        if (!method_exists($controller, $actionName)) {
            throw new ResponseException(500, ['message' => 'Action not found']);
        }

        //call controller method
        try {
            $controller->$actionName();
        } catch (ZBFException $e) {
            throw new ResponseException(500, ['message' => 'Error while calling action'], $e);
        }

        $this->response->send();

    }
}
