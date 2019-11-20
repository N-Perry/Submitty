<?php namespace tests\app\libraries\homework\UseCases\LibraryGet;


use app\libraries\homework\Entities\LibraryEntity;
use app\libraries\homework\UseCases\LibraryGetUseCase;
use app\libraries\homework\UseCases\LibraryGetResponse;
use tests\app\libraries\homework\UseCases\BaseTestCase;

class LibraryGetTester extends BaseTestCase {

    /** @var LibraryGetUseCase */
    protected $useCase;

    /** @var LibraryGetResponse */
    protected $response;

    public function setUp(): void {
        parent::setUp();

        $this->useCase = new LibraryGetUseCase($this->core);
    }

    protected function handleTest() {
        $this->response = $this->useCase->getLibraries();
    }


    /** @test */
    public function testItShouldReturnEmpty() {
        $this->handleTest();

        $this->assertEquals([], $this->response->getResults());
    }

    /** @test */
    public function testItShouldReturnResults() {
        $this->libraryGateway->addLibrary(new LibraryEntity('name', $this->location));
        $this->libraryGateway->addLibrary(new LibraryEntity('name2', $this->location));

        $this->handleTest();

        $this->assertEquals([
            'name',
            'name2'
        ], $this->response->getResults());
    }
}
