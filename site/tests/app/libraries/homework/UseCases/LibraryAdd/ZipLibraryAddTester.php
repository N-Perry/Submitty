<?php namespace tests\app\libraries\homework\UseCases\LibraryAdd;

use app\libraries\homework\Entities\LibraryEntity;
use tests\app\libraries\homework\UseCases\BaseTestCase;
use app\libraries\homework\UseCases\LibraryAddUseCase;
use app\libraries\homework\UseCases\LibraryAddResponse;

class ZipLibraryAddTester extends BaseTestCase {
    /** @var LibraryAddUseCase */
    protected $useCase;

    /** @var LibraryAddResponse */
    protected $response;

    public function setUp(): void {
        parent::setUp();

        $this->useCase = new LibraryAddUseCase($this->core);
    }

    protected function handleTest($zipFile) {
        $this->response = $this->useCase->addZipLibrary($zipFile);
    }

    /** @test */
    public function testItDoesNotAddNullFilePath() {
        $this->handleTest(null);

        $this->assertEquals('A file must be provided.', $this->response->error);
    }

    /** @test */
    public function testItDoesNotAddEmptyFilePath() {
        $this->handleTest([]);

        $this->assertEquals('A file must be provided.', $this->response->error);
    }

    /** @test */
    public function testItRequiresNameAttribute() {
        $this->handleTest([
            'tmp_name' => 'Thanos did nothing wrong'
        ]);

        $this->assertEquals('A file must be provided.', $this->response->error);
    }

    /** @test */
    public function testItRequiresTmpNameAttribute() {
        $this->handleTest([
            'name' => 'I didn\'nt save work and lost a bunch of stuff here :( So now I\'m having to rewrite it.'
        ]);

        $this->assertEquals('A file must be provided.', $this->response->error);
    }

    /** @test */
    public function testItDoesNotAddNonZipFiles() {
        $this->handleTest([
            'name' => 'definitely not a zip file.avi',
            'tmp_name' => 'save your work boys...'
        ]);

        $this->assertEquals('A .zip file must be provided.', $this->response->error);
    }

    public function testItDoesNotAddAnInvalidFileName() {
        $this->handleTest([
            'name' => '/the/full/file/path/lib.zip',
            'tmp_name' => 'I\'m slightly tired.'
        ]);

        $this->assertEquals('Invalid file name.', $this->response->error);
    }

    /** @test */
    public function testItDoesNotOverwriteLibraries() {
        $this->libraryGateway->addLibrary(new LibraryEntity(
            'lib',
            $this->location
        ));

        $this->handleTest([
            'name' => 'lib.zip',
            'tmp_name' => 'tmp name'
        ]);

        $this->assertEquals('Error adding the library. Library already exists', $this->response->error);
    }

    /** @test */
    public function testItAddsValidZip() {
        $this->handleTest([
            'name' => 'lib.zip',
            'tmp_name' => '171926 ;)'
        ]);

        $this->assertEquals('Successfully installed new library: lib', $this->response->getMessage());
        $this->assertTrue($this->libraryGateway->libraryExists(new LibraryEntity(
            'lib',
            $this->location
        )));
    }
}
