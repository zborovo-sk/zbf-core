<?php

namespace ZborovoSK\ZBFCore\Http;

use ZborovoSK\ZBFCore\ZBFException;
use ZborovoSK\ZBFCore\Http\Http;

class Request
{
    /**
     * @var string request method - GET, POST, PUT, DELETE
     */
    private string $method = Http::METHOD_GET;

    /**
     * @var string request path - /path
     */
    private string $path = '/';

    /**
     * @var array query params - /path?param=value => ['param' => 'value']
     */
    private array $query = [];

    /**
     * @var array url params - /path/{param} => ['param' => 'value']
     */
    private array $params = [];

    /**
     * @var array cookies
     */
    private array $cookies = [];

    /**
     * @var array headers
     */
    private array $headers = [];

    /**
     * @var string raw body of request
     */
    private string $rawBody = '';

    /**
     * @var array body of request
     */
    private array $body = [];

    /**
     * @var string controller class name
     */
    private string $controllerClass = '';

    /**
     * @var string action name
     */
    private string $actionName = '';

    /**
     * parse path
     * @var string $originalPath
     * @return string
     */
    private function parsePath(string $originalPath): string
    {
        $path = $originalPath;

        //remove query string
        if(strpos($path, '?') !== false) {
            $path = substr($path, 0, strpos($path, '?'));
        }
        $path = rtrim($path, '/');

        //if path is empty, set it to /
        if(empty($path)) {
            $path = '/';
        }

        return $path;
    }


    public function __construct()
    {
        //set method
        $this->method = $_SERVER['REQUEST_METHOD'];

        //set query params
        $this->query = $_GET;

        //set path
        $this->path = $this->parsePath($_SERVER['REQUEST_URI']);

        //set cookies
        $this->cookies = $_COOKIE;

        //set headers
        $this->headers = getallheaders();

        //set raw body
        $this->rawBody = file_get_contents('php://input');

        //set body - based on requests content type
        if (in_array($this->method, [
            Http::METHOD_POST,
            Http::METHOD_PUT,
            Http::METHOD_PATCH,
            Http::METHOD_DELETE
        ])) {

            if (
                isset($this->headers['Content-Type']) &&
                strpos($this->headers['Content-Type'], Http::CONTENT_TYPE_JSON) !== false
            ) {
                try {
                    $this->body = json_decode($this->rawBody, true);
                } catch (\Exception $e) {
                    throw new ZBFException('Invalid JSON in request body');
                }
            } else {
                //urlencoded and multipart/form-data are parsed to POST array automatically by PHP
                $this->body = $_POST;
            }

        }

    }

    /**
     * Sets params
     * @param array $params
     * @return void
     */
    public function setParams(array $params): void
    {
        $this->params = $params;
    }

    /**
     * Sets controller class name
     * @param string $controllerClass
     * @return void
     */
    public function setControllerClass(string $controllerClass): void
    {
        $this->controllerClass = $controllerClass;
    }

    /**
     * Sets action name
     * @param string $actionName
     * @return void
     */
    public function setActionName(string $actionName): void
    {
        $this->actionName = $actionName;
    }

    /**
     * Get method
     * @return string
     */
    public function getMethod(): string
    {
        return $this->method;
    }

    /**
     * Get path
     * @return string
     */
    public function getPath(): string
    {
        return $this->path;
    }

    /**
     * Get controller class name
     * @return string
     */
    public function getControllerClass(): string
    {
        return $this->controllerClass;
    }

    /**
     * Get action name
     * @return string
     */
    public function getActionName(): string
    {
        return $this->actionName;
    }

    /**
     * Has Header
     * @param string $key
     * @return bool
     */
    public function hasHeader(string $key): bool
    {
        return array_key_exists($key, $this->headers);
    }

    /**
     * Get Header
     * @param string $key
     * @param string $default
     * @return string
     */
    public function getHeader(string $key, string $default = ''): string
    {
        return $this->headers[$key] ?? $default;
    }

    /**
     * Get headers
     * @return array
     */
    public function getHeaders(): array
    {
        return $this->headers;
    }

    /**
     * Has body param
     * @param string $key
     * @return bool
     */
    public function hasBodyParam(string $key): bool
    {
        return array_key_exists($key, $this->body);
    }

    /**
     * Get body param
     * @param mixed $key
     * @param mixed $default
     * @return string
     */
    public function getBodyParam(string $key, string $default = ''): mixed
    {
        return $this->body[$key] ?? $default;
    }

    /**
     * Get body
     * @return array
     */
    public function getBody()
    {
        return $this->body;
    }

    /**
     * Get raw body
     * @return string
     */
    public function getRawBody(): string
    {
        return $this->rawBody;
    }

    /**
     * Has query param
     * @param string $key
     * @return bool
     */
    public function hasQueryParam(string $key): bool
    {
        return array_key_exists($key, $this->query);
    }

    /**
     * Get query param
     * @param string $key
     * @param string $default
     * @return string
     */

    public function getQueryParam(string $key, string $default = ''): string
    {
        return $this->query[$key] ?? $default;
    }

    /**
     * Get query
     * @return array
     */
    public function getQuery(): array
    {
        return $this->query;
    }

    /**
     * Has cookie
     * @param string $key
     * @return bool
     */
    public function hasCookie(string $key): bool
    {
        return array_key_exists($key, $this->cookies);
    }

    /**
     * Get cookie
     * @param string $key
     * @param string $default
     * @return string
     */
    public function getCookie(string $key, string $default = ''): string
    {
        return $this->cookies[$key] ?? $default;
    }

    /**
     * Get cookies
     * @return array
     */
    public function getCookies(): array
    {
        return $this->cookies;
    }

    /**
     * Has url param
     * @param string $key
     * @return bool
     */
    public function hasUrlParam(string $key): bool
    {
        return array_key_exists($key, $this->params);
    }

    /**
     * Get url param
     * @param string $key
     * @param string $default
     * @return string
     */
    public function getUrlParam(string $key, string $default = ''): string
    {
        return $this->params[$key] ?? $default;
    }

    /**
     * Get url params
     * @return array
     */
    public function getUrlParams(): array
    {
        return $this->params;
    }
}
