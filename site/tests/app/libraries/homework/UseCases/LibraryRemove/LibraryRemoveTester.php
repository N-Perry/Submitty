<?php namespace tests\app\libraries\homework\UseCases\LibraryRemove;


use app\libraries\homework\Entities\LibraryEntity;
use tests\app\libraries\homework\UseCases\BaseTestCase;
use app\libraries\homework\UseCases\LibraryRemoveUseCase;
use app\libraries\homework\UseCases\LibraryRemoveResponse;

class LibraryRemoveTester extends BaseTestCase {
    /** @var LibraryRemoveUseCase */
    protected $useCase;

    /** @var LibraryRemoveResponse */
    protected $response;

    public function setUp(): void {
        parent::setUp();

        $this->useCase = new LibraryRemoveUseCase($this->core);
    }

    public function handleTest($library) {
        $this->response = $this->useCase->removeLibrary($library);
    }

    /** @test */
    public function testRemoveLibrary() {
        $library = new LibraryEntity('name', $this->location);
        $this->libraryGateway->addLibrary($library);

        $this->handleTest($library->getName());

        $this->assertFalse($this->libraryGateway->libraryExists($library));
        $this->assertEquals('Successfully removed library \'name\'', $this->response->getMessage());
    }

    /** @test */
    public function testRemoveEmptyLibraryWithNullName() {
        $this->handleTest(null);

        $this->assertEquals('You must specify the library to remove.', $this->response->error);
    }

    /** @test */
    public function testRemoveEmptyLibraryWithEmptyName() {
        $this->handleTest('');

        $this->assertEquals('You must specify the library to remove.', $this->response->error);
    }

    public function testFailToRemoveLibrary() {
        $this->handleTest('fail to remove');

        $this->assertEquals('Error when removing library \'fail to remove\'', $this->response->error);
    }

}
