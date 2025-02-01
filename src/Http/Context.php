<?php

namespace ZborovoSK\ZBFCore\Http;

use ZborovoSK\ZBFCore\ZBFException;

class Context
{
    /**
     * @var array data
     */
    private string $data = [];

    /**
     * Set data to context
     * @param string $key
     * @param mixed $value
     */
    public function set(string $key, $value): void
    {
        $this->data[$key] = $value;
    }

    /**
     * Get data from context
     * @param string $key
     * @param mixed $default
     * @return mixed
     */
    public function get(string $key, $default = null)
    {
        if (array_key_exists($key, $this->data)) {
            return $this->data[$key];
        }

        return $default;
    }

    /**
     * setup pseudo setters and getters
     */
    public function __call($name, $arguments)
    {
        if (strpos($name, 'set') === 0) {
            $key = lcfirst(substr($name, 3));
            $this->set($key, $arguments[0]);
        } elseif (strpos($name, 'get') === 0) {
            $key = lcfirst(substr($name, 3));
            return $this->get($key, $arguments[0] ?? null);
        } else {
            throw new ZBFException("Method $name not found");
        }
    }
}
