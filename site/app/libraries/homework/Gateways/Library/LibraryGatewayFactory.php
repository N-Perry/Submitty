<?php namespace app\libraries\homework\Gateways\Library;

use app\libraries\homework\Gateways\LibraryGateway;

class LibraryGatewayFactory {
    /** @var LibraryGateway */
    protected static $instance;

    /**
     * Lazy load the library singleton
     *
     * @return LibraryGateway
     */
    public static function getInstance() {
        if (!static::$instance) {
            static::$instance = new FileSystemLibraryGateway;
        }

        return static::$instance;
    }

    /**
     * Set the LibraryGateway singleton
     *
     * @param LibraryGateway $gateway
     */
    public static function setInstance(LibraryGateway $gateway) {
        static::$instance = $gateway;
    }

    /**
     * Clear the LibraryGateway singleton
     */
    public static function clearInstance() {
        static::$instance = null;
    }

}
