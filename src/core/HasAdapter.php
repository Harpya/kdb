<?php

namespace harpya\kdb\core;

trait HasAdapter
{
    protected $adapter;

    /**
     * Get the value of adapter
     */
    public function getAdapter()
    {
        if (!$this->adapter) {
            throw new \Exception("Adapter is not configured");
        }
        return $this->adapter;
    }

    /**
     * Set the value of adapter
     *
     * @return  self
     */
    public function setAdapter($adapter)
    {
        $this->adapter = $adapter;

        return $this;
    }
}
