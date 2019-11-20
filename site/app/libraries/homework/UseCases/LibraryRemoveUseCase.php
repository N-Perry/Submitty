<?php namespace app\libraries\homework\UseCases;

use app\libraries\Core;
use app\libraries\homework\Entities\LibraryEntity;
use app\libraries\homework\Gateways\Library\LibraryGatewayFactory;
use app\libraries\homework\Gateways\LibraryGateway;

class LibraryRemoveResponse {
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

    public static function error(string $message): LibraryRemoveResponse {
        $response = new static;
        $response->error = $message;
        return $response;
    }
}

class LibraryRemoveUseCase extends BaseUseCase {
    /** @var LibraryGateway */
    protected $gateway;

    public function __construct(Core $core) {
        parent::__construct($core);

        $this->gateway = LibraryGatewayFactory::getInstance();
    }

    /**
     * Removes a library from the library repository by name
     *
     * @param string|null $name
     * @return LibraryRemoveResponse
     */
    public function removeLibrary($name): LibraryRemoveResponse {
        if (!$name) {
            return LibraryRemoveResponse::error('You must specify the library to remove.');
        }

        $library = new LibraryEntity($name, $this->location);

        if ($this->gateway->removeLibrary($library)) {
            return new LibraryRemoveResponse("Successfully removed library '$name'");
        }

        return LibraryRemoveResponse::error("Error when removing library '$name'");
    }

}
