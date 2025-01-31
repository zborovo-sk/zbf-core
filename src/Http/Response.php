<?php

namespace ZborovoSK\ZBFCore\Http;

use ZborovoSK\ZBFCore\ZBFException;

class ResponseCookie
{
    public string $key;
    public string $value;
    public int $expire;
    public string $path = '/';
    public string $domain = '';
    public bool $secure = false;
    public bool $httpOnly = false;

    public function __construct(
        string $key,
        string $value,
        int $expire = null,
        string $path = null,
        string $domain = null,
        bool $secure = false,
        bool $httpOnly = false
    ) {
        $this->key = $key;
        $this->value = $value;

        if ($expire) {
            $this->expire = $expire;
        } else {
            $this->expire = time() + 3600;
        }

        if ($path) {
            $this->path = $path;
        }

        if ($domain) {
            $this->domain = $domain;
        }

        $this->secure = $secure;
        $this->httpOnly = $httpOnly;
    }
}

class Response
{
    private string $data = '';
    private int $statusCode = 200;
    private array $headers = [];
    /**
     * @var ResponseCookie[]
     */
    private array $cookies = [];

    public function addHeader(string $key, string $value): void
    {
        $this->headers[$key] = $value;
    }

    public function addCookie(
        string $key,
        string $value,
        int $expire = null,
        string $path = null,
        string $domain = null,
        bool $secure = false,
        bool $httpOnly = false): void
    {
        $this->cookies[] = new ResponseCookie($key, $value, $expire, $path, $domain, $secure, $httpOnly);
    }

    public function redirect(string $url): void
    {
        header('Location: ' . $url);
        exit;
    }

    public function setStatusCode(int $statusCode): void
    {
        $this->statusCode = $statusCode;
    }

    public function setData(string $data): void
    {
        $this->data = $data;
    }

    public function send(): void
    {
        //set status code
        http_response_code($this->statusCode);

        //set headers
        foreach ($this->headers as $key => $value) {
            header($key . ': ' . $value);
        }

        //set cookies
        foreach ($this->cookies as $cookie) {
            setcookie(
                $cookie->key,
                $cookie->value,
                $cookie->expire,
                $cookie->path,
                $cookie->domain,
                $cookie->secure,
                $cookie->httpOnly
            );
        }
        echo $this->data;
    }

    public function json(array $data): void
    {
        $this->addHeader('Content-Type', 'application/json');
        $this->setData(json_encode($data));
        $this->send();
    }

    public function html(string $data): void
    {
        $this->addHeader('Content-Type', 'text/html');
        $this->setData($data);
        $this->send();
    }
}