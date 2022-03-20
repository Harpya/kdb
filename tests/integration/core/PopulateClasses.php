<?php

namespace tests\integration\core;

trait PopulateClasses
{
    /**
     * @req[R.003]. The user should be able to define Classes
     *
     * @param string $namespace
     * @return array
     */
    protected function populateClassesDefinition($namespace='global'): array
    {
        // @req[R.003]. The user should be able to define Classes
        $this->getDataModelManager()->selectNamespace($namespace);

        $classesToAdd = [];
        $classesToAdd[] = [
            'name' => 'folder',
            'attributes' => [
                'name' => [
                    'type' => 'string'
                ],
                'permissions' => [
                    'type' => 'string'
                ],
                'createdAt' => [
                    'type' => 'timestamp'
                ]
            ],
            'props' => []
        ];

        $classesToAdd[] = [
            'name' => 'file',
            'attributes' => [
                'name' => [
                    'type' => 'string'
                ],
                'permissions' => [
                    'type' => 'string'
                ],
                'createdAt' => [
                    'type' => 'timestamp'
                ]
            ],
            'props' => []
        ];


        $classesToAdd[] = [
            'name' => 'git/repository',
            'attributes' => [
                'name' => [
                    'type' => 'string'
                ],
                'description' => [
                    'type' => 'text'
                ],
                'url' => [
                    'type' => 'url'
                ]
            ],
            'props' => []
        ];

        $classesToAdd[] = [
            'name' => 'git/branch',
            'attributes' => [
                'name' => [
                    'type' => 'string'
                ],
                'url' => [
                    'type' => 'url'
                ]
            ],
            'props' => []
        ];

        $classesToAdd[] = [
            'name' => 'git/commit',
            'attributes' => [
                'name' => [
                    'type' => 'string'
                ],
                'url' => [
                    'type' => 'url'
                ],
                'comments' => [
                    'type' => 'string'
                ],
            ],
            'props' => []
        ];

        $classesToAdd[] = [
            'name' => 'file-modifications-in-commit',
            'attributes' => [
                'changes' => [
                    'type' => 'text'
                ],
                'timeOfChange' => [
                    'type' => 'timestamp'
                ],
                'whoChanged' => [
                    'type' => 'reference'
                ]
            ],
            'props' => []
        ];


        foreach ($classesToAdd as $item) {
            $this->getDataModelManager()->defineClass($item['name'], $item['attributes'], $item['props']);
        }
        return $classesToAdd;
    }
}
