<?php namespace tests\app\libraries\homework\Gateways;

use app\libraries\Core;
use app\libraries\FileUtils;
use app\libraries\Utils;
use tests\BaseUnitTest;

class BaseTestCase extends BaseUnitTest {
    /** @var Core */
    protected $core;

    /** @var string */
    protected $location;

    public function setUp(): void {
        parent::setUp();
        $this->location = FileUtils::joinPaths(sys_get_temp_dir(), Utils::generateRandomString());
        FileUtils::createDir($this->location);

        $this->core = $this->createMockCore([
            'homework_library_enable' => true,
            'homework_library_location' => $this->location
        ]);
    }

    /**
     * Cleanup routine for the tester. This deletes any folders/files we created in the tmp directory to hold our fake
     * uploaded files.
     */
    public function tearDown(): void {
        $this->assertTrue(FileUtils::recursiveRmdir($this->location));
    }
}
