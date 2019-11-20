<?php namespace tests\app\libraries\homework\UseCases\LibraryAdd;


use app\libraries\homework\Entities\LibraryEntity;
use tests\app\libraries\homework\UseCases\BaseTestCase;
use app\libraries\homework\UseCases\LibraryAddResponse;
use app\libraries\homework\UseCases\LibraryAddUseCase;

class GitLibraryAddTester extends BaseTestCase {
    /** @var LibraryAddUseCase */
    protected $useCase;

    /** @var LibraryAddResponse */
    protected $response;

    public function setUp(): void {
        parent::setUp();

        $this->useCase = new LibraryAddUseCase($this->core);
    }

    protected function handleTest($gitUrl) {
        $this->response = $this->useCase->addGitLibrary($gitUrl);
    }

    /** @test */
    public function testItDoesNotAddNullGitUrl() {
        $this->handleTest(null);

        $this->assertEquals('A repo url is required.', $this->response->error);
    }

    /** @test */
    public function testItDoesNotAddEmptyGitUrl() {
        $this->handleTest('');

        $this->assertEquals('A repo url is required.', $this->response->error);
    }

    /** @test */
    public function testItDoesNotAddInvalidGitUrl() {
        $this->handleTest('not an actual git url lol');

        $this->assertEquals('The git url is not of the right format.',
            $this->response->error);
    }

    /** @test */
    public function testItDoesNotOverwriteLibraries() {
        $this->libraryGateway->addLibrary(new LibraryEntity('lib', $this->location));

        $this->handleTest('git@github.com:user/lib.git');

        $this->assertEquals('Error adding the library. Library already exists', $this->response->error);
    }

    /** @test */
    public function testCreatesAValidLibrary() {
        $this->handleTest('git@github.com:user/lib.git');

        $this->assertEquals('Successfully cloned git@github.com:user/lib.git.', $this->response->getMessage());
        $this->assertTrue($this->libraryGateway->libraryExists(new LibraryEntity('lib', $this->location)));
    }
}
