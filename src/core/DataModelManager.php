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



    /**
     * @req[R.003]. The user should be able to define Classes
     *
     * @param string $name
     * @param array $attributes
     * @param array $props
     * @return array
     */
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

    /**
     * @req[R.012]. The user should be able to retrieve all defined `Classes`
     *
     * @return array
     */
    public function getAllClasses()
    {
        return $this->getAdapter()->getAllClasses();
    }

    public function removeClassDefinitionByName($name)
    {
        return $this->getAdapter()->removeClassDefinitionByName($name);
    }


    /**
     * @req[R.004]. The user should be able to define Associations types among Classes
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


    /**
     * @req[R.014]. The user should be able to get all `Association Types` linked to a given `Class`.
     *
     * @param string $name
     * @return array
     */
    public function getAssociationsByOriginClass($name)
    {
        return $this->getAdapter()->getAssociationsByOriginClass($name);
    }

    /**
     * @req[R.014]. The user should be able to get all `Association Types` linked to a given `Class`.
     *
     * @param string $name
     * @return array
     */
    public function getAssociationsByTargetClass($name)
    {
        return $this->getAdapter()->getAssociationsByTargetClass($name);
    }
}
