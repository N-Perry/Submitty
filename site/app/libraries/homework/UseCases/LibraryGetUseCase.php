<?php namespace app\libraries\homework\UseCases;


use app\libraries\Core;
use app\libraries\homework\Entities\LibraryEntity;
use app\libraries\homework\Gateways\Library\LibraryGatewayFactory;
use app\libraries\homework\Gateways\LibraryGateway;

class LibraryGetResponse {
    public $error;

    /** @var string[] */
    protected $libraries = [];

    public function addLibrary(string $lib) {
        $this->libraries[] = $lib;
    }

    public function getResults(): array {
        return $this->libraries;
    }

    public static function error(string $message) {
        $response = new static;
        $response->error = $message;
        return $response;
    }
}


class LibraryGetUseCase extends BaseUseCase {

    /** @var LibraryGateway */
    protected $gateway;

    public function __construct(Core $core) {
        parent::__construct($core);

        $this->gateway = LibraryGatewayFactory::getInstance();
    }

    public function getLibraries(): LibraryGetResponse {
        $response = new LibraryGetResponse();

        $libraries = $this->gateway->getAllLibraries($this->location);

        /** @var LibraryEntity $library */
        foreach ($libraries as $library) {
            $response->addLibrary($library->getName());
        }

        return $response;
    }
}
