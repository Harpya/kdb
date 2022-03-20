<?php

namespace harpya\kdb\core;

class DataIngestor
{
    use HasAdapter;



    /**
     * @req[R.006]. The user should be able to add `Objects` of a given `Class`
     *
     * @param string $name
     * @param string $type
     * @param array $attributes
     * @return array
     */
    public function addObject($name, $type, $attributes=[])
    {
        return $this->getAdapter()->addObject($name, $type, $attributes);
    }


    public function getAllObjects(): array
    {
        $list = $this->getAdapter()->getAllObjects();

        return $list;
    }

    /**
     * @req[R.008]. The user should be able to add Associations among two `Objects`]
     *
     * @param string $origin
     * @param string $target
     * @param string $type
     * @param array $attributes
     * @param array $props
     * @return array
     */
    public function addAssociation($origin, $target, $type, $attributes=[], $props=[])
    {
        $this->verifyObjectExists($origin);
        $this->verifyObjectExists($target);

        return $this->getAdapter()->addAssociation($origin, $target, $type, $attributes, $props);
    }

    /**
     * @req[R.013]. The user should be able to get all defined `Association Types`
     *
     * @return array
     */
    public function getAllAssociations(): array
    {
        $list = $this->getAdapter()->getAllAssociations();
        return $list;
    }



    /**
     * @req[R.007]. The system should issue an error if an Object is trying to be added, but it's specified Class was not defined
     *
     * @param string $name
     * @return void
     * @throws \Exception
     */
    protected function verifyObjectExists($name)
    {
        if (!$this->getAdapter()->getObjectByName($name)) {
            throw new \Exception("Object $name was not found");
        }
    }
}
