<?php

namespace tests\integration\core;

use harpya\kdb\adapters\AdapterBase;

use function PHPUnit\Framework\assertEquals;

require_once __DIR__.'/DataCommons.php';
require_once __DIR__.'/PopulateNamespaces.php';
require_once __DIR__.'/PopulateClasses.php';
require_once __DIR__.'/PopulateAssociations.php';

class DataModelManagerTest extends DataCommons
{
    use PopulateNamespaces;
    use PopulateClasses;
    use PopulateAssociations;

    public function testInvokingDefineClassShouldAddEntry()
    {
        $this->getDataModelManager()->defineClass('folder', [
            'permissions' => [
                'type' => 'string'
            ],
            'createdAt' => [
                'type' => 'timestamp'
            ]
        ]);

        $folderSpecs = $this->getDataModelManager()->getClass('folder');

        $this->assertTrue(is_array($folderSpecs));
        $this->assertArrayHasKey(AdapterBase::FIELD_ATTRIBUTES, $folderSpecs);
        $this->assertCount(2, $folderSpecs[AdapterBase::FIELD_ATTRIBUTES]);
    }





    /**
     * @req[R.012]. The user should be able to retrieve all defined `Classes`
     *
     * @return void
     */
    public function testDefineMultipleClassesShouldAddMultipleClassEntries()
    {
        $this->getDataModelManager()->reset();

        // @req[R.001]. The user should be able to create Namespaces, as Container for Classes, Association types, Objects and Associations
        $namespace = $this->populateNamespaces();

        // @req[R.002]. The user should be able to select the Namespace to work
        $expectedSelectedNamespace = $namespace[0]['name'];
        $classesDefinitionsAdded = $this->populateClassesDefinition($expectedSelectedNamespace);
        $classes = $this->getDataModelManager()->getAllClasses();
        $this->assertCount(count($classesDefinitionsAdded), $classes);

        // Switching from namespace A to B, should replace the current state...
        $this->getDataModelManager()->selectNamespace();
        $this->assertEquals('global', $this->getDataModelManager()->getSelectedNamespace());
        $classes = $this->getDataModelManager()->getAllClasses();
        $this->assertCount(0, $classes);


        // Restoring to namespace A, should return to initial state...
        $this->getDataModelManager()->selectNamespace($expectedSelectedNamespace);
        $this->assertEquals($expectedSelectedNamespace, $this->getDataModelManager()->getSelectedNamespace());
        // Repeating the same operations, but now with the first dataset
        $classes = $this->getDataModelManager()->getAllClasses();
        $this->assertCount(count($classesDefinitionsAdded), $classes);
    }



    public function testAddClassesAndRemoveSomeDefinitions()
    {
        $this->getDataModelManager()->reset();
        $this->populateClassesDefinition();

        $classes = $this->getDataModelManager()->getAllClasses();
        $currElementsCount = count($classes);

        $this->getDataModelManager()->removeClassDefinitionByName('git/repository');

        $classes = $this->getDataModelManager()->getAllClasses();
        $this->assertCount($currElementsCount-1, $classes);
    }

    public function testUpdateClassesDefinitionsWillReplaceOnDB()
    {
        $this->getDataModelManager()->reset();
        $this->populateClassesDefinition();

        // Before
        $folderSpecs = $this->getDataModelManager()->getClass('folder');

        $this->assertTrue(is_array($folderSpecs));
        $this->assertArrayHasKey(AdapterBase::FIELD_ATTRIBUTES, $folderSpecs);
        $this->assertCount(3, $folderSpecs[AdapterBase::FIELD_ATTRIBUTES]);


        // Replacinf 'folder' definition
        $this->getDataModelManager()->defineClass('folder', [
            'name' => [
                'type' => 'string'
            ],
            'permissions' => [
                'type' => 'string'
            ],
            'createdAt' => [
                'type' => 'timestamp'
            ],
            'owner' => [
                'type' => 'reference'
            ]
        ]);

        // After
        $folderSpecs = $this->getDataModelManager()->getClass('folder');

        $this->assertTrue(is_array($folderSpecs));
        $this->assertArrayHasKey(AdapterBase::FIELD_ATTRIBUTES, $folderSpecs);
        $this->assertCount(4, $folderSpecs[AdapterBase::FIELD_ATTRIBUTES]);
        $this->assertArrayHasKey('owner', $folderSpecs[AdapterBase::FIELD_ATTRIBUTES]);
    }



    public function testAddClassesAndRemoveSomeDefinitionsAndRelatedAssociations()
    {
        $this->getDataModelManager()->reset();
        $this->populateClassesDefinition();
        $this->populateAssociations();

        $classes = $this->getDataModelManager()->getAllClasses();
        $currElementsCount = count($classes);
        $associations = $this->getDataModelManager()->getAllAssociationTypes();
        $currAssociationsCount = count($associations);

        $this->assertTrue($currAssociationsCount>0, 'Associations definitions count should be greater than zero');

        $this->getDataModelManager()->removeClassDefinitionByName('git/repository');

        $classes = $this->getDataModelManager()->getAllClasses();
        $this->assertCount($currElementsCount-1, $classes);

        $associations = $this->getDataModelManager()->getAllAssociationTypes();
        // After remove a class, all related associations should be removed as well
        $this->assertTrue(count($associations) < $currAssociationsCount, 'ensure total association definitions is smaller than previous count');
    }



    /**
     * @req[R.014]. The user should be able to get all `Association Types` linked to a given `Class`.
     *
     * @return void
     */
    public function testAddRecursiveDependencyInAClassDefinition()
    {
        $this->getDataModelManager()->reset();
        $this->populateClassesDefinition();
        $this->populateAssociations();

        $this->getDataModelManager()->defineAssociationType(
            'folder',
            'folder',
            'has-parent',
            [],
            [],
        );

        $folderOriginAssociations = $this->getDataModelManager()->getAssociationsByOriginClass('folder');
        $folderTargetAssociations = $this->getDataModelManager()->getAssociationsByTargetClass('folder');

        $this->assertTrue(is_array($folderOriginAssociations));
        $this->assertTrue(is_array($folderTargetAssociations));

        $this->assertCount(2, $folderOriginAssociations);
        $this->assertCount(2, $folderTargetAssociations);
    }
}
