<?php

namespace fifo;

/**
 * Class Fifo
 * @package fifo
 */
class Fifo
{
    /**
     * @var int
     */
    protected $capacity;

    /**
     * @var array
     */
    protected $cache = [];

    /**
     * @var array
     */
    protected $order = [];

    /**
     * Fifo constructor.
     * @param int $capacity
     */
    public function __construct($capacity)
    {
        $this->capacity = $capacity;
    }

    /**
     * @param string $key
     * @return bool
     */
    protected function exists($key)
    {
        return isset($this->cache[$key]);
    }

    /**
     * @param string $key
     * @return mixed|null
     */
    public function get($key)
    {
        return $this->exists($key) ? $this->cache[$key] : null;
    }

    /**
     * @param string $key
     * @param mixed|null $value
     */
    public function put($key, $value)
    {
        if ($this->overCapacity()) {
            $this->remove(array_shift($this->order));
        }

        $this->order[] = $key;
        $this->cache[$key] = $value;
    }

    /**
     * @param string $key
     * @return bool
     */
    public function remove($key)
    {
        if ($this->exists($key)) {
            unset($this->cache[$key]);

            return true;
        }

        return false;
    }

    /**
     * @return bool
     */
    protected function overCapacity()
    {
        return count($this->order) + 1 > $this->capacity;
    }
}