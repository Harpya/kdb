<?php

namespace tests\unit\core;

use PHPUnit\Framework\TestCase;

use harpya\kdb\core\DataModelManager;
use harpya\kdb\adapters\InMemmoryAdapter;

class DataModelManagerTest extends TestCase
{
    public function testCreateDataModelManagerWithSuccess()
    {
        $obj = new DataModelManager();
        $this->assertTrue(\is_object($obj));
    }

    public function testAssignAdapterToDataModelManagerWithSuccess()
    {
        $obj = new DataModelManager();
        $adapter = new InMemmoryAdapter();
        $obj->setAdapter($adapter);
        $this->assertTrue(\is_object($obj->getAdapter()));
        $this->assertEquals(get_class($adapter), get_class($obj->getAdapter()));
    }

    public function testUseDataModelManagerWithoutAdapterConfiguredShouldFail()
    {
        $obj = new DataModelManager();

        $this->expectExceptionMessage("Adapter is not configured");
        $this->assertTrue(\is_object($obj->getAdapter()));
    }
}
