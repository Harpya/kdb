<?php

namespace harpya\kdb\core;

class DataModelManager
{
    use HasAdapter;

    public function createNamespace($name, $props=[])
    {
        return $this->getAdapter()->createNamespace($name, $props);
    }

    public function selectNamespace($name='global')
    {
        return $this->getAdapter()->selectNamespace($name);
    }

    public function getSelectedNamespace()
    {
        return $this->getAdapter()->getSelectedNamespace();
    }




    public function defineClass($name, $attributes=[], $props=[])
    {
        return $this->getAdapter()->defineClass($name, $attributes, $props);
    }

    public function getClass($name): array
    {
        $resp = $this->getAdapter()->getClass($name);
        return $resp;
    }

    public function reset()
    {
        $this->getAdapter()->reset();
    }

    public function getAllClasses()
    {
        return $this->getAdapter()->getAllClasses();
    }

    public function removeClassDefinitionByName($name)
    {
        return $this->getAdapter()->removeClassDefinitionByName($name);
    }


    /**
     * Undocumented function
     *
     * @param string $classesOrigin
     * @param string $classesTarget
     * @param string $type
     * @param array $attributes
     * @param array $props
     * @return array
     */
    public function defineAssociationType($classesOrigin, $classesTarget, $type, $attributes=[], $props=[])
    {
        $response = [];

        $this->getAdapter()->defineAssociationType($classesOrigin, $classesTarget, $type, $attributes, $props);

        return $response;
    }

    public function getAllAssociationTypes()
    {
        return $this->getAdapter()->getAllAssociationTypes();
    }


    public function getAssociationsByOriginClass($name)
    {
        return $this->getAdapter()->getAssociationsByOriginClass($name);
    }

    public function getAssociationsByTargetClass($name)
    {
        return $this->getAdapter()->getAssociationsByTargetClass($name);
    }
}
