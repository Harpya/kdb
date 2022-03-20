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

    public function getAllObjects(): array
    {
        return $this->dataset;
    }



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

    public function getAllAssociations(): array
    {
        return $this->associations;
    }

    public function getObjectsAssociatedToObjectByName($name): array
    {
        $list = $this->getAssociationsByOrigin($name);

        $response = [];

        foreach ($list as $key) {
            $assoc = $this->associations[$key];
            $objResponse = $this->getObjectByName($assoc['target']);
            $objResponse['_assoc_'] = $assoc;

            if (isset($assoc['props']['associativeObject'])) {
                $objResponse['associativeObject'] = $this->getObjectByName($assoc['props']['associativeObject']);
            }
            $response[] = $objResponse;
        }

        return $response;
    }


    public function getAssociationsByOrigin($name): array
    {
        return $this->originAssociations[$name] ?? [];
    }


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
