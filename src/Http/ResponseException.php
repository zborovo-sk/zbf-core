<?php

namespace ZborovoSK\ZBFCore\Http;

use ZborovoSK\ZBFCore\ZBFException;
use ZborovoSK\ZBFCore\Http\Http;
use ZborovoSK\ZBFCore\Http\Response;
use ZborovoSK\ZBFCore\Http\Request;
use ZborovoSK\ZBFCore\Http\Context;
use Exception;

class ResponseException extends ZBFException
{
    private int $statusCode;
    private array $data;

    public function __construct(int $statusCode = 500, array $data = [], $message = "", Exception $previous = null)
    {
        $this->statusCode = $statusCode;
        $this->data = $data;

        parent::__construct($message, 0, $previous);
    }

    public function render(Response $response, Request $request, Context $context): void
    {
        $response->setStatusCode($this->statusCode);

        //check request headers for Accept, if there is none check for requests content type
        $isJson = false;

        if ($request->hasHeader('Accept')) {
            $accept = $request->getHeader('Accept');
            $isJson = strpos($accept, Http::CONTENT_TYPE_JSON) !== false;
        } elseif ($request->hasHeader('Content-Type')) {
            $contentType = $request->getHeader('Content-Type');
            $isJson = strpos($contentType, Http::CONTENT_TYPE_JSON) !== false;
        }

        if ($isJson) {
            $response->setHeader('Content-Type', Http::CONTENT_TYPE_JSON);
            $response->setData(json_encode($this->data));
        } else {
            $response->setHeader('Content-Type', Http::CONTENT_TYPE_HTML);
            $response->setData($this->data['message']);
        }
    }
}
