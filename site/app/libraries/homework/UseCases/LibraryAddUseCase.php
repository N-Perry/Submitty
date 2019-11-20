<?php namespace app\libraries\homework\UseCases;

use app\libraries\Core;
use app\libraries\FileUtils;
use app\libraries\homework\Entities\LibraryEntity;
use app\libraries\homework\Gateways\LibraryGateway;
use app\libraries\homework\Gateways\Library\LibraryGatewayFactory;

class LibraryAddResponse {
    /** @var string */
    protected $message;

    /** @var string */
    public $error;

    public function __construct(string $message = '') {
        $this->message = $message;
    }

    public function getMessage(): string {
        return $this->message;
    }

    public static function error(string $message): LibraryAddResponse {
        $response = new static;
        $response->error = $message;
        return $response;
    }

}

class LibraryAddUseCase extends BaseUseCase {
    /** @var LibraryGateway */
    protected $gateway;

    public function __construct(Core $core) {
        parent::__construct($core);

        $this->gateway = LibraryGatewayFactory::getInstance();
    }

    /**
     * Takes a string representing the git url to clone, and adds it to the library
     *
     * @param null|string $repoUrl
     * @return LibraryAddResponse
     */
    public function addGitLibrary($repoUrl): LibraryAddResponse {
        if (!$repoUrl) {
            return LibraryAddResponse::error('A repo url is required.');
        }

        // Regex can be viewed in detail here.
        // https://www.debuggex.com/r/H4kRw1G0YPyBFjfm
        // It validates .git repository urls.
        if (!preg_match(
            '/((git|ssh|http(s)?)|(git@[\w\.]+))(:(\/\/)?)([\w\.@\:\/\-~]+)(\.git)(\/)?/',
            $repoUrl,
            $matches
        )) {
            return LibraryAddResponse::error('The git url is not of the right format.');
        }

        /*
         * From the link above, one can easily see that group 7 is the wanted group.
         * We use index 7 because index 0 from preg match is the whole string.
         * We then split and take the name which is usually at the end of the url.
         * This will not work for same repo names with different authors, so
         * that will probably want to be fixed later.
         */
        $parts = explode('/', $matches[7]);
        $libName = array_pop($parts);

        $library = new LibraryEntity($libName, $this->location);

        $status = $this->gateway->addGitLibrary($library, $repoUrl);

        if (!$status->library) {
            return LibraryAddResponse::error('Error adding the library. ' . $status->message);
        }

        return new LibraryAddResponse("Successfully cloned $repoUrl.");
    }

    /**
     * Takes in a $_FILES file and adds it to the library
     *
     * @param array|null $zipFile
     * @return LibraryAddResponse
     */
    public function addZipLibrary($zipFile): LibraryAddResponse {
        if (!$zipFile || !isset($zipFile['name']) || !isset($zipFile['tmp_name'])) {
            return LibraryAddResponse::error('A file must be provided.');
        }

        $name = $zipFile['name'];
        $tmpName = $zipFile['tmp_name'];

        if (!FileUtils::isValidFileName($name) || strpos($name, '/') !== FALSE) {
            return LibraryAddResponse::error('Invalid file name.');
        }

        $parts = explode('.', $name);

        $extension = array_pop($parts);

        if (strtolower($extension) != 'zip' || count($parts) < 1) {
            return LibraryAddResponse::error('A .zip file must be provided.');
        }

        $libName = implode('.', $parts);

        $library = new LibraryEntity($libName, $this->location);

        $status = $this->gateway->addZipLibrary($library, $tmpName);

        if (!$status->library) {
            return LibraryAddResponse::error('Error adding the library. ' . $status->message);
        }

        return new LibraryAddResponse("Successfully installed new library: $libName");
    }
}
