<?php

namespace ArgentCrusade\Support\Traits;

trait IteratorTrait
{
    /**
     * @var array
     */
    protected $items = [];

    /**
     * @var array
     */
    protected $keys = [];

    /**
     * @var int
     */
    protected $position = 0;

    /**
     * Reset iterator position.
     */
    protected function reset()
    {
        $this->keys = array_keys($this->items);
        $this->position = 0;
    }

    /**
     * Get current item.
     *
     * @return mixed
     */
    public function current()
    {
        return $this->items[$this->key()];
    }

    /**
     * Increment position and return new value.
     */
    public function next()
    {
        ++$this->position;
    }

    /**
     * Get current key.
     *
     * @return mixed
     */
    public function key()
    {
        return $this->keys[$this->position];
    }

    /**
     * Determines if current position's key exists.
     *
     * @return bool
     */
    public function valid()
    {
        return isset($this->items[$this->keys[$this->position]]);
    }

    /**
     * Reset iterator position.
     */
    public function rewind()
    {
        $this->reset();
    }
}
