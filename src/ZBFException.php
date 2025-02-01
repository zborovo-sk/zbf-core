<?php

namespace ZborovoSK\ZBFCore;

use Exception;

class ZBFException extends Exception
{
    public function __construct($message = "", $code = 0, Exception $previous = null)
    {
        parent::__construct($message, $code, $previous);
    }
}
