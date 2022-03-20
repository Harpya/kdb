<?php

namespace harpya\kdb\core;

class DataIngestor
{
    use HasAdapter;



    public function addObject($name, $type, $attributes=[])
    {
        $this->getAdapter()->addObject($name, $type, $attributes);
    }


    public function getAllObjects(): array
    {
        $list = $this->getAdapter()->getAllObjects();

        return $list;
    }

    public function addAssociation($origin, $target, $type, $attributes=[], $props=[])
    {
        $this->verifyObjectExists($origin);
        $this->verifyObjectExists($target);

        return $this->getAdapter()->addAssociation($origin, $target, $type, $attributes, $props);
    }

    public function getAllAssociations(): array
    {
        $list = $this->getAdapter()->getAllAssociations();
        return $list;
    }


    protected function verifyObjectExists($name)
    {
        if (!$this->getAdapter()->getObjectByName($name)) {
            throw new \Exception("Object $name was not found");
        }
    }
}
