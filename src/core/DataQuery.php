<?php

namespace harpya\kdb\core;

class DataQuery
{
    use HasAdapter;


    /**
     *
     * @param string $objName
     * @return array
     */
    public function getObjectsAssociatedToObjectByName($objName): array
    {
        return $this->getAdapter()->getObjectsAssociatedToObjectByName($objName);
    }
}
