<?php namespace tests\app\libraries\homework\Gateways\Library;

use ZipArchive;
use app\libraries\FileUtils;
use app\libraries\homework\Entities\LibraryEntity;
use app\libraries\homework\Entities\LibraryAddStatus;
use tests\app\libraries\homework\Gateways\BaseTestCase;
use app\libraries\homework\Gateways\Library\LibraryGatewayFactory;
use app\libraries\homework\Gateways\Library\FileSystemLibraryGateway;

class FileSystemLibraryGatewayTester extends BaseTestCase {
    /** @var FileSystemLibraryGateway */
    protected $gateway;

    const VALID_GIT_URL = "https://github.com/Submitty/Submitty.git";

    public function setUp(): void {
        parent::setUp();

        $this->gateway = new FileSystemLibraryGateway();
    }

    protected function createTestZip(string $zipName): string {
        $zip = new ZipArchive();

        $f = FileUtils::joinPaths($this->location, $zipName);

        $res = $zip->open($f, ZipArchive::CREATE);
        $this->assertTrue($res);
        $zip->addFromString('test.txt', 'File content. Hurray.');
        $zip->close();
        return $f;
    }

    /** @test */
    public function testItClonesAGitRepository() {
        $library = new LibraryEntity('Submitty', $this->location);
        $return = $this->gateway->addGitLibrary($library, self::VALID_GIT_URL);

        $this->assertLibraryAddStatusSuccess($return, $library);
        $this->assertDirectoryExists($library->getLibraryPath());
        $this->assertDirectoryExists(FileUtils::joinPaths($library->getLibraryPath(), '.git'));
    }

    /** @test */
    public function testItFailsCloningABadGitRepository() {
        $library = new LibraryEntity('name', $this->location);

        $return = $this->gateway->addGitLibrary($library, 'invalid url');

        $this->assertLibraryAddStatusError(
            $return,
            'Error cloning repository. fatal: repository \'invalid url\' does not exist'
        );
        $this->assertDirectoryNotExists($library->getLibraryPath());
    }

    /** @test */
    public function testItDoesNotAddAnInvalidZipFile() {
        $library = new LibraryEntity('name', $this->location);
        $return = $this->gateway->addZipLibrary($library, 'invalid zip');

        $this->assertLibraryAddStatusError($return, 'Error opening zip file.');
        $this->assertDirectoryNotExists($library->getLibraryPath());
    }

    /** @test */
    public function testItAddsAZipFile() {
        $zip = $this->createTestZip('test.zip');

        $library = new LibraryEntity('test', $this->location);

        $return = $this->gateway->addZipLibrary($library, $zip);
        $this->assertLibraryAddStatusSuccess($return, $library);
        $this->assertDirectoryExists($library->getLibraryPath());
        $this->assertFileExists($library->getLibraryPath() . '/test.txt');
    }

    /** @test */
    public function testItRetrievesAllLibrariesWhenEmpty() {
        $results = $this->gateway->getAllLibraries($this->location);

        $this->assertEquals([], $results);
    }

    /** @test */
    public function testItRetrievesAllLibraries() {
        FileUtils::createDir(FileUtils::joinPaths($this->location, 'lib1'));
        FileUtils::createDir(FileUtils::joinPaths($this->location, 'lib2'));
        FileUtils::createDir(FileUtils::joinPaths($this->location, 'lib3'));

        /** @var LibraryEntity[] $results */
        $results = $this->gateway->getAllLibraries($this->location);

        $this->assertCount(3, $results);
        $this->assertEquals('lib1', $results[0]->getName());
        $this->assertEquals('lib2', $results[1]->getName());
        $this->assertEquals('lib3', $results[2]->getName());
    }

    /** @test */
    public function testItDoesNotOverwriteLibrariesZip() {
        FileUtils::createDir(FileUtils::joinPaths($this->location, 'name'));
        $library = new LibraryEntity('name', $this->location);
        $status = $this->gateway->addZipLibrary($library, 'invalid zip');

        $this->assertLibraryAddStatusError($status, 'Library already exists.');
    }

    /** @test */
    public function testItDoesNotOverwriteLibrariesGit() {
        FileUtils::createDir(FileUtils::joinPaths($this->location, 'name'));
        $library = new LibraryEntity('name', $this->location);

        $status = $this->gateway->addGitLibrary($library, 'url');

        $this->assertLibraryAddStatusError($status, 'Library already exists.');
    }

    /**
     * @param LibraryAddStatus $status
     * @param LibraryEntity $library
     */
    protected function assertLibraryAddStatusSuccess(LibraryAddStatus $status, LibraryEntity $library) {
        $this->assertNotNull($status->library);
        $this->assertTrue($library->is($status->library));
        $this->assertEquals(LibraryAddStatus::SUCCESS, $status->message);
    }

    /**
     * @param LibraryAddStatus $status
     * @param string $message
     */
    protected function assertLibraryAddStatusError(LibraryAddStatus $status, string $message) {
        $this->assertNull($status->library);

        $this->assertEquals($message, $status->message);
    }

    /** @test */
    public function testItRemovesLibraries() {
        $library = new LibraryEntity('name', $this->location);
        FileUtils::createDir($library->getLibraryPath());

        $this->assertDirectoryExists($library->getLibraryPath());
        $this->assertTrue($this->gateway->removeLibrary($library));
        $this->assertDirectoryNotExists($library->getLibraryPath());
    }

    /** @test */
    public function testItRemovesNonExistentLibraries() {
        $library = new LibraryEntity('name', $this->location);

        $this->assertTrue($this->gateway->removeLibrary($library));
        $this->assertDirectoryNotExists($library->getLibraryPath());
    }

    /** @test */
    public function testTheDefaultGatewayIsFileSystemGateway() {
        LibraryGatewayFactory::clearInstance();
        $instance = LibraryGatewayFactory::getInstance();
        $this->assertInstanceOf(FileSystemLibraryGateway::class, $instance);
    }
}
