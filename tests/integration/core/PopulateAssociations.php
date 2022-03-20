<?php

namespace tests\integration\core;

trait PopulateAssociations
{
    protected function populateAssociations()
    {
        $associationsToAdd = [];

        $associationsToAdd[] = [
            'type' => 'contains',
            'description' => 'folder-contains-files',
            'origin' => 'folder',
            'target' => 'file',
            'attributes' => [],
            'props' => [],
        ];

        $associationsToAdd[] = [
            'type' => 'contained',
            'description' => 'file-is-in-folder',
            'origin' => 'file',
            'target' => 'folder',
            'attributes' => [],
            'props' => [],
        ];

        $associationsToAdd[] = [
            'type' => 'contains',
            'description' => 'folder-contains-files',
            'origin' => 'folder',
            'target' => 'file',
            'attributes' => [],
            'props' => [],
        ];



        $associationsToAdd[] = [
            'type' => 'contains',
            'description' => 'git/repository-contains-git/branches',
            'origin' => 'git/repository',
            'target' => 'git/branch',
            'attributes' => [],
            'props' => [],
        ];

        $associationsToAdd[] = [
            'type' => 'belongs',
            'description' => 'git/branch-belongs-git/repository',
            'origin' => 'git/branch',
            'target' => 'git/repository',
            'attributes' => [],
            'props' => [],
        ];




        $associationsToAdd[] = [
            'type' => 'contains',
            'description' => 'git/branch-contains-git/commit',
            'origin' => 'git/branch',
            'target' => 'git/commit',
            'attributes' => [],
            'props' => [],
        ];

        $associationsToAdd[] = [
            'type' => 'belongs',
            'description' => 'git/commit-belongs-git/branch',
            'origin' => 'git/commit',
            'target' => 'git/branch',
            'attributes' => [],
            'props' => [],
        ];



        /**
         * This is necessary to keep track of changes in a file, in a commit.
         * Since a `commit` may be linked to multiple files,
         * AND a file may have changes in multiple commits, the solution is to
         * store the reference of a unique associative object, shared among 2
         * different associations:
         */
        $associationsToAdd[] = [
            'type' => 'has',
            'description' => 'git/commit-has-file',
            'origin' => 'git/commit',
            'target' => 'file',
            'attributes' => [],
            'props' => [
                'associativeObject' => 'file-modifications-in-commit'
            ],
        ];

        $associationsToAdd[] = [
            'type' => 'has',
            'description' => 'file-has-git/commit',
            'origin' => 'file',
            'target' => 'git/commit',
            'attributes' => [],
            'props' => [
                'associativeObject' => 'file-modifications-in-commit'
            ],
        ];



        foreach ($associationsToAdd as $item) {
            $item['props']['description'] = $item['description'] ?? '';

            $this->getDataModelManager()->defineAssociationType(
                $item['origin'],
                $item['target'],
                $item['type'],
                $item['attributes'],
                $item['props'],
            );
        }
        return $associationsToAdd;
    }
}
