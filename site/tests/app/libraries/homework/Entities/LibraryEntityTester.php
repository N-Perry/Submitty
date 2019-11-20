<?php namespace tests\app\libraries\homework\Entities;


use PHPUnit\Framework\TestCase;
use app\libraries\homework\Entities\LibraryEntity;

class LibraryEntityTester extends TestCase {

    /** @test */
    public function testItSetsAttributes() {
        $entity = new LibraryEntity('name', 'path');

        $this->assertEquals('name', $entity->getName());
        $this->assertEquals('path', $entity->getLocation());
    }

    /** @test */
    public function testItSetsLocationProperly() {
        $entity = new LibraryEntity('name', "/var/www/test/ \n\r");

        $this->assertEquals('/var/www/test', $entity->getLocation());
    }

    /** @test */
    public function testItBuildsCorrectPaths() {
        $entity = new LibraryEntity('name', 'path');

        $this->assertEquals('path/name', $entity->getLibraryPath());
    }


    /** @test */
    public function testItComparesLocations() {
        $entity = new LibraryEntity('name', 'path');

        $this->assertTrue($entity->hasLocationOf("path/ \n\r"));
    }

    /** @test */
    public function testItComparesOtherEntities() {
        $entity = new LibraryEntity('name', 'path');
        $otherEntity = new LibraryEntity('name', 'path');

        $this->assertTrue($entity->is($otherEntity));
        $this->assertFalse($entity->isNot($otherEntity));
        $this->assertTrue($entity->hasNameOf($otherEntity->getName()));
        $this->assertTrue($entity->hasLocationOf($otherEntity->getLocation()));
    }
}
