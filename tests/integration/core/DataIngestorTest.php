<?php

namespace tests\integration\core;

use harpya\kdb\core\DataIngestor;
use harpya\kdb\core\DataQuery;

use function PHPUnit\Framework\assertEquals;

require_once __DIR__.'/DataCommons.php';
require_once __DIR__.'/PopulateClasses.php';
require_once __DIR__.'/PopulateAssociations.php';


class DataIngestorTest extends DataCommons
{
    use PopulateClasses;
    use PopulateAssociations;

    /**
     * Undocumented variable
     *
     * @var DataIngestor
     */
    protected $dataIngestor;


    /**
     * Undocumented variable
     *
     * @var DataQuery
     */
    protected $dataQuery;



    protected $expectedCountObjectsAdded = 0;
    protected $expectedCountAssociationsAdded = 0;


    public function getDataIngestor()
    {
        if (!$this->dataIngestor) {
            $this->dataIngestor = new DataIngestor();
        }
        return $this->dataIngestor;
    }



    /**
     *
     * @return DataQuery
     */
    public function getDataQuery(): DataQuery
    {
        if (!$this->dataQuery) {
            $this->dataQuery = new DataQuery();
        }
        return $this->dataQuery;
    }




    protected function setUp(): void
    {
        parent::setUp();

        // Setup
        $this->getDataModelManager()->reset();

        $this->populateClassesDefinition();
        $this->populateAssociations();

        $this->getDataIngestor()->setAdapter($this->getDataModelManager()->getAdapter());
        $this->getDataQuery()->setAdapter($this->getDataModelManager()->getAdapter());
    }


    protected function populateDefaultDataset()
    {
        $this->getDataIngestor()->addObject('/', 'folder', []);
        $this->getDataIngestor()->addObject('/home', 'folder', []);
        $this->getDataIngestor()->addObject('/var', 'folder', []);
        $this->getDataIngestor()->addObject('/home/admin', 'folder', []);

        $this->getDataIngestor()->addObject('/home/admin/composer.json', 'file', ['permissions'=>'755']);
        $this->getDataIngestor()->addObject('/home/admin/.gitignore', 'file', ['permissions'=>'755']);

        $this->getDataIngestor()->addObject('myGitRepository', 'git/repository', ['url'=>'git://mygit.com/myGitRepository.git']);
        $this->getDataIngestor()->addObject('myGitRepository/master', 'git/branch', ['url'=>'git://mygit.com/myGitRepository.git/branches/master']);
        $this->getDataIngestor()->addObject('myGitRepository/dev', 'git/branch', ['url'=>'git://mygit.com/myGitRepository.git/branches/dev']);
        $this->getDataIngestor()->addObject('myGitRepository/some-ticket', 'git/branch', ['url'=>'git://mygit.com/myGitRepository.git/branches/some-ticket']);

        $this->getDataIngestor()->addObject('myGitRepository/a000001', 'git/commit', ['url'=>'git://mygit.com/myGitRepository.git/commits/a000001']);
        $this->getDataIngestor()->addObject('myGitRepository/a000002', 'git/commit', ['url'=>'git://mygit.com/myGitRepository.git/commits/a000002']);
        $this->getDataIngestor()->addObject('myGitRepository/a000003', 'git/commit', ['url'=>'git://mygit.com/myGitRepository.git/commits/a000003']);

        // First way to add an association among a file and the commit
        $this->getDataIngestor()->addObject('myGitRepository/a000003-/home/admin/composer.json', 'file-modifications', ['whoChanged'=>'user1']);
        $this->getDataIngestor()->addAssociation('myGitRepository/a000003', '/home/admin/composer.json', 'has', [], ['associativeObject' => 'myGitRepository/a000003-/home/admin/composer.json']);
        $this->getDataIngestor()->addAssociation('/home/admin/composer.json', 'myGitRepository/a000003', 'has', [], ['associativeObject' => 'myGitRepository/a000003-/home/admin/composer.json']);
        $this->expectedCountAssociationsAdded += 2;



        $this->expectedCountObjectsAdded = 14;


        // Helper methods to easily add folders hierarchically
        $this->addFolderToFolderAssociations('/', '/home');
        $this->addFolderToFolderAssociations('/home', '/home/admin');
        $this->addFolderToFolderAssociations('/', '/var');

        $this->addFolderToFileAssociations('/home/admin', '/home/admin/composer.json');
    }


    protected function addFolderToFolderAssociations($parent, $child)
    {
        $this->getDataIngestor()->addAssociation($parent, $child, 'contains');
        $this->getDataIngestor()->addAssociation($child, $parent, 'contained');
        $this->expectedCountAssociationsAdded += 2;
    }

    protected function addFolderToFileAssociations($folder, $file)
    {
        $this->getDataIngestor()->addAssociation($folder, $file, 'contains');
        $this->getDataIngestor()->addAssociation($file, $folder, 'contained');
        $this->expectedCountAssociationsAdded += 2;
    }



    public function testCreateObjectsWithSuccess()
    {
        $this->getDataIngestor()->addObject('/', 'folder', []);

        $lsObjects = $this->getDataIngestor()->getAllObjects();

        $this->assertTrue(is_array($lsObjects));
        $this->assertCount(1, $lsObjects);
    }

    public function testCreateObjectsAndAssociationsWithSuccess()
    {
        $this->populateDefaultDataset();

        $lsObjects = $this->getDataIngestor()->getAllObjects();
        $lsAssociations = $this->getDataIngestor()->getAllAssociations();


        $this->assertTrue(is_array($lsObjects));
        $this->assertCount($this->expectedCountObjectsAdded, $lsObjects);

        $this->assertTrue(is_array($lsAssociations));
        $this->assertCount($this->expectedCountAssociationsAdded, $lsAssociations);
    }



    public function testFetchFolders()
    {
        $this->populateDefaultDataset();

        $lsFoldersByParent = $this->getDataQuery()->getObjectsAssociatedToObjectByName('/');

        $this->assertTrue(is_array($lsFoldersByParent));
        $this->assertCount(2, $lsFoldersByParent);

        $parentVarFolder = $this->getDataQuery()->getObjectsAssociatedToObjectByName('/var');

        $this->assertTrue(is_array($parentVarFolder));
        $this->assertCount(1, $parentVarFolder);
        $this->assertArrayHasKey(0, $parentVarFolder);
        $this->assertArrayHasKey('name', $parentVarFolder[0]);
        $this->assertEquals('/', $parentVarFolder[0]['name']);
        $this->assertEquals('folder', $parentVarFolder[0]['type']);
    }


    public function testAddAssociationWithNonexistentObjectShouldFail()
    {
        $this->populateDefaultDataset();

        $filename = '/home/admin/composer.json';

        $this->expectException(\Exception::class);

        $this->getDataIngestor()->addAssociation($filename, 'non-existent-object', 'contains');
    }

    public function testAddCommitsForAFile()
    {
        $this->populateDefaultDataset();

        $filename = '/home/admin/composer.json';
        $lsAssociatedObjects = $this->getDataQuery()->getObjectsAssociatedToObjectByName($filename);

        $this->assertTrue(is_array($lsAssociatedObjects));
        $this->assertCount(2, $lsAssociatedObjects);

        $this->getDataIngestor()->addObject('myGitRepository/a000004', 'git/commit', ['url'=>'git://mygit.com/myGitRepository.git/commits/a000004']);

        // First way to add an association among a file and the commit
        $this->getDataIngestor()->addObject('myGitRepository/a000004-/home/admin/composer.json', 'file-modifications', ['whoChanged'=>'user2']);
        $this->getDataIngestor()->addAssociation('myGitRepository/a000004', '/home/admin/composer.json', 'has', [], ['associativeObject' => 'myGitRepository/a000004-/home/admin/composer.json']);
        $this->getDataIngestor()->addAssociation('/home/admin/composer.json', 'myGitRepository/a000004', 'has', [], ['associativeObject' => 'myGitRepository/a000004-/home/admin/composer.json']);

        $lsAssociatedObjects = $this->getDataQuery()->getObjectsAssociatedToObjectByName($filename);

        // print_r($lsAssociatedObjects);

        $this->assertCount(3, $lsAssociatedObjects);

        foreach ($lsAssociatedObjects as $obj) {
            if ($obj['name'] == 'myGitRepository/a000004') {
                $this->assertArrayHasKey('associativeObject', $obj);
            }
        }
    }
}
