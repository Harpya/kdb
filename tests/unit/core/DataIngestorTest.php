<?php

namespace tests\unit\core;

use PHPUnit\Framework\TestCase;

use harpya\kdb\core\DataIngestor;

class DataIngestorTest extends TestCase
{
    public function testCreateDataIngestorWithSuccess()
    {
        $obj = new DataIngestor();

        $this->assertTrue(\is_object($obj));
    }
}
