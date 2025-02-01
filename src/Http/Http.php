<?php

namespace ZborovoSK\ZBFCore\Http;

class Http
{
    //constants for content types
    public const CONTENT_TYPE_JSON = 'application/json';
    public const CONTENT_TYPE_FORM = 'application/x-www-form-urlencoded';
    public const CONTENT_TYPE_MULTIPART = 'multipart/form-data';
    public const CONTENT_TYPE_HTML = 'text/html';
    public const CONTENT_TYPE_TEXT = 'text/plain';
    public const CONTENT_TYPE_XML = 'application/xml';

    //constants for methods
    public const METHOD_GET = 'GET';
    public const METHOD_POST = 'POST';
    public const METHOD_PUT = 'PUT';
    public const METHOD_PATCH = 'PATCH';
    public const METHOD_DELETE = 'DELETE';
}
