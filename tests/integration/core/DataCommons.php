<?php

namespace tests\integration\core;

use PHPUnit\Framework\TestCase;

use harpya\kdb\core\DataModelManager;
use harpya\kdb\adapters\InMemmoryAdapter;

class DataCommons extends TestCase
{
    /**
     *
     * @var DataModelManager
     */
    protected $dataModelManager;

    protected $adapter;



    protected function getAdapter(): InMemmoryAdapter
    {
        if (!$this->adapter) {
            $this->adapter = new InMemmoryAdapter();
        }
        return $this->adapter;
    }


    protected function getDataModelManager(): DataModelManager
    {
        if (!$this->dataModelManager) {
            $this->dataModelManager = new DataModelManager();
            $this->dataModelManager->setAdapter($this->getAdapter());
        }
        return $this->dataModelManager;
    }
}
