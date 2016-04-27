<?php

namespace fifo;

class Fifo
{
    protected $capacity;

    protected $cache = [];

    protected $order = [];

    public function __construct($capacity)
    {
        $this->capacity = $capacity;
    }

    protected function exists($key)
    {
        return isset($this->cache[$key]);
    }

    public function get($key)
    {
        return $this->exists($key) ? $this->cache[$key] : null;
    }

    public function put($key, $value)
    {
        if ($this->overCapacity()) {
            $this->remove(array_shift($this->order));
        }

        $this->order[] = $key;
        $this->cache[$key] = $value;
    }

    public function remove($key)
    {
        if ($this->exists($key)) {
            unset($this->cache[$key]);

            return true;
        }

        return false;
    }

    protected function overCapacity()
    {
        return count($this->order) + 1 > $this->capacity;
    }
}