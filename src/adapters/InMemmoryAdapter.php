<?php

namespace harpya\kdb\adapters;

class InMemmoryAdapter extends AdapterBase implements AdapterInterface
{
    protected $namespaces = [
        'global'=>[
            'data' => [],
            'props' => []
        ]
    ];

    protected $currentNamespace = 'global';


    // Metamodel
    protected $classes = [];
    protected $associationTypes = [];

    protected $originAssociationTypes = [];
    protected $targetAssociationTypes = [];

    // Data
    protected $dataset = [];

    protected $associations = [];

    protected $originAssociations = [];


    public function createNamespace($name, $props=[])
    {
        if (!isset($this->namespaces[$name])) {
            $this->namespaces[$name] = [
                'props'=>$props,
                'data' => []
            ];
        }
        return $this->namespaces[$name];
    }

    public function selectNamespace($name)
    {
        $this->saveData();
        $this->currentNamespace = $name;
        $this->loadData();
    }

    public function getSelectedNamespace()
    {
        return $this->currentNamespace;
    }

    protected function saveData()
    {
        // if (!isset($this->namespaces[$this->currentNamespace]['data'])) {
        //     $this->namespaces[$this->currentNamespace]['data'] = [];
        // }
        $arrfields = [
            'classes',
            'associationTypes',
            'originAssociationTypes',
            'targetAssociationTypes',
            'dataset',
            'associations',
            'originAssociations',
    ];
        $data = [];
        foreach ($arrfields as $fieldName) {
            $data[$fieldName] = $this->$fieldName;
        }
        $this->namespaces[$this->currentNamespace]['data'] = $data;
    }

    protected function loadData()
    {
        $arrfields = [
            'classes',
            'associationTypes',
            'originAssociationTypes',
            'targetAssociationTypes',
            'dataset',
            'associations',
            'originAssociations',
        ];
        $data = $this->namespaces[$this->currentNamespace]['data'] ?? [];
        foreach ($arrfields as $fieldName) {
            $this->$fieldName = $data[$fieldName] ?? [];
        }
        // $this->namespaces[$this->currentNamespace]['data'] = $data;
    }

    public function defineClass($name, $attributes=[], $props=[])
    {
        $definedClass = [
            self::FIELD_NAME => $name,
            self::FIELD_ATTRIBUTES => $attributes,
            self::FIELD_PROPERTIES => $props
        ];

        $this->classes[$name] = $definedClass;

        return $definedClass;
    }


    public function defineAssociationType($classesOrigin, $classesTarget, $type, $attributes=[], $props=[])
    {
        $key =  strtolower("{$classesOrigin}-$type-{$classesTarget}");

        $definedAssociation = [
            self::FIELD_KEY => $key,
            self::FIELD_TYPE => $type,
            self::FIELD_ATTRIBUTES => $attributes,
            self::FIELD_PROPERTIES => $props,
        ];

        $this->addOriginIndex($classesOrigin, $key);
        $this->addTargetIndex($classesTarget, $key);

        $this->associationTypes[$key] = $definedAssociation;
        return $definedAssociation;
    }


    protected function addOriginIndex($classesOrigin, $key)
    {
        if (!isset($this->originAssociationTypes[$classesOrigin])) {
            $this->originAssociationTypes[$classesOrigin] = [];
        }
        $this->originAssociationTypes[$classesOrigin][] = $key;
    }

    protected function addTargetIndex($classesTarget, $key)
    {
        if (!isset($this->targetAssociationTypes[$classesTarget])) {
            $this->targetAssociationTypes[$classesTarget] = [];
        }
        $this->targetAssociationTypes[$classesTarget][] = $key;
    }



    public function getClass($name)
    {
        return $this->classes[$name] ?? null;
    }

    public function reset()
    {
        $this->classes = [];
        $this->associationTypes = [];
    }

    public function getAllClasses()
    {
        return $this->classes;
    }

    public function removeClassDefinitionByName($name)
    {
        if (isset($this->classes[$name])) {
            unset($this->classes[$name]);
        }

        $this->removeIndexDependenciesByName($name);

        return $this->classes;
    }


    protected function removeIndexDependenciesByName($name)
    {
        // removing dependencies
        if (isset($this->originAssociationTypes[$name])) {
            foreach ($this->originAssociationTypes[$name] as $key) {
                $this->removeAssociationTypeByKey($key);
            }
            unset($this->originAssociationTypes[$name]);
        }

        if (isset($this->targetAssociationTypes[$name])) {
            foreach ($this->targetAssociationTypes[$name] as $key) {
                $this->removeAssociationTypeByKey($key);
            }
            unset($this->targetAssociationTypes[$name]);
        }
    }



    public function getAllAssociationTypes(): array
    {
        return $this->associationTypes;
    }

    protected function removeAssociationTypeByKey($key)
    {
        if (isset($this->associationTypes[$key])) {
            unset($this->associationTypes[$key]);
        }
    }


    /**
     * @req[R.014]. The user should be able to get all `Association Types` linked to a given `Class`.
     *
     * @param string $name
     * @return array
     */
    public function getAssociationsByOriginClass($name)
    {
        $list = [];

        if (isset($this->originAssociationTypes[$name])) {
            foreach ($this->originAssociationTypes[$name] as $key) {
                if (isset($this->associationTypes[$key])) {
                    $list[$key] = $this->associationTypes[$key];
                }
            }
        }

        return $list;
    }


    /**
     * @req[R.014]. The user should be able to get all `Association Types` linked to a given `Class`.
     *
     * @param string $name
     * @return array
     */
    public function getAssociationsByTargetClass($name)
    {
        $list = [];

        if (isset($this->targetAssociationTypes[$name])) {
            foreach ($this->targetAssociationTypes[$name] as $key) {
                if (isset($this->associationTypes[$key])) {
                    $list[$key] = $this->associationTypes[$key];
                }
            }
        }

        return $list;
    }



    /**
     * @req[R.006]. The user should be able to add `Objects` of a given `Class`
     *
     * @param string $name
     * @param string $type
     * @param array $attributes
     * @return array
     */
    public function addObject($name, $type, $attributes)
    {
        $key = "{$type}-{$name}";

        $obj = [
            self::FIELD_NAME => $name,
            self::FIELD_TYPE => $type,
            self::FIELD_ATTRIBUTES => $attributes
        ];

        $this->dataset[$key] = $obj;

        return $obj;
    }

    /**
     * @req[R.009]. The user should be able to retrieve all `Objects` in a `Namespace`
     *
     * @return array
     */
    public function getAllObjects(): array
    {
        return $this->dataset;
    }



    /**
     * @req[R.008]. The user should be able to add Associations among two `Objects`
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
        $key =  strtolower("{$origin}-$type-{$target}");

        $association = [
            self::FIELD_TYPE => $type,
            self::FIELD_ORIGIN => $origin,
            self::FIELD_TARGET => $target,
            self::FIELD_ATTRIBUTES => $attributes,
            self::FIELD_PROPERTIES => $props,
        ];

        $this->associations[$key] = $association;

        if (!isset($this->originAssociations[$origin])) {
            $this->originAssociations[$origin] = [];
        }
        $this->originAssociations[$origin][] = $key;

        return $association;
    }

    /**
     * @req[R.013]. The user should be able to get all defined `Association Types`
     *
     * @return array
     */
    public function getAllAssociations(): array
    {
        return $this->associations;
    }

    /**
     * @req[R.011]. The user should be able to retrieve all `Objects` associated with a given `Object's` name
     *
     * @param string $name
     * @return array
     */
    public function getObjectsAssociatedToObjectByName($name): array
    {
        $list = $this->getAssociationsByOrigin($name);

        $response = [];

        foreach ($list as $key) {
            $assoc = $this->associations[$key];
            $objResponse = $this->getObjectByName($assoc['target']);
            $objResponse['_assoc_'] = $assoc;

            $this->checkAndFillAssociativeObjectField($assoc, $objResponse);

            $response[] = $objResponse;
        }

        return $response;
    }


    /**
     * @req[R.005]. The user should be able to define relationships with `Associative` `Classes`
     *
     * @param array $assoc
     * @param array $objResponse
     * @return void
     */
    protected function checkAndFillAssociativeObjectField(&$assoc, &$objResponse)
    {
        if (isset($assoc['props']['associativeObject'])) {
            $objResponse['associativeObject'] = $this->getObjectByName($assoc['props']['associativeObject']);
        }
    }



    protected function getAssociationsByOrigin($name): array
    {
        return $this->originAssociations[$name] ?? [];
    }


    /**
     * @req[R.010]. The user should be able to retrieve an `Object` by its name
     *
     * @param [type] $name
     * @return array
     */
    public function getObjectByName($name): array
    {
        foreach ($this->dataset as  $obj) {
            if ($obj['name'] == $name) {
                return $obj;
            }
        }
        return [];
    }
}
