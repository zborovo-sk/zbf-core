<?php

namespace ZborovoSK\ZBFCore\MVC;

use ZborovoSK\ZBFCore\Http\Request;
use ZborovoSK\ZBFCore\Http\Response;
use ZborovoSK\ZBFCore\Http\Context;
use ZborovoSK\ZBFCore\MVC\View;
use ZborovoSK\ZBFCore\ZBFException;


class Controller
{
    protected Request $request;
    protected Response $response;
    protected Context $context;

    protected View $view;

    public function __construct(Request $request, Response $response, Context $context)
    {
        $this->request = $request;
        $this->response = $response;
        $this->context = $context;
        $this->view = new View($context);
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
}
