<?php

namespace tests\integration\core;

trait PopulateNamespaces
{
    protected function populateNamespaces()
    {
        $namespacesToAdd = [
            ['name'=>'test-kdb', 'props'=>[]],
            ['name'=>'project-A', 'props'=>[]],
        ];


        foreach ($namespacesToAdd as $item) {
            $this->getDataModelManager()->createNamespace($item['name'], $item['props']);
        }
        return $namespacesToAdd;
    }
}
