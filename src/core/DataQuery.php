<?php

namespace harpya\kdb\core;

class DataQuery
{
    use HasAdapter;


    /**
     * @req[R.011]. The user should be able to retrieve all `Objects` associated with a given `Object's` name
     * @param string $objName
     * @return array
     */
    public function getObjectsAssociatedToObjectByName($objName): array
    {
        return $this->getAdapter()->getObjectsAssociatedToObjectByName($objName);
    }


    /**
     * @req[R.010]. The user should be able to retrieve an `Object` by its name
     *
     * @param string $name
     * @return array
     */
    public function getObjectByName($name)
    {
        return $this->getAdapter()->getObjectByName($name);
    }
}
