<?php namespace app\libraries\homework\UseCases;


use app\libraries\Core;
use app\libraries\homework\Entities\LibraryEntity;
use app\libraries\homework\Gateways\LibraryGateway;
use app\libraries\homework\Gateways\Library\LibraryGatewayFactory;

class LibraryGetResponse {
    /** @var string[] */
    protected $libraries = [];

    public function addLibrary(string $lib) {
        $this->libraries[] = $lib;
    }

    /**
     * Returns an array the library names
     *
     * @return string[]
     */
    public function getResults(): array {
        return $this->libraries;
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
