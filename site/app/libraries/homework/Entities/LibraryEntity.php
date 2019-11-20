<?php namespace app\libraries\homework\Entities;

class LibraryAddStatus {
    const SUCCESS = 'success';

    /** @var LibraryEntity|null */
    public $library;

    /** @var string */
    public $message;

    /**
     * @param string $error
     * @return LibraryAddStatus
     */
    public static function error(string $error): LibraryAddStatus {
        return new static(null, $error);
    }

    /**
     * @param LibraryEntity $library
     * @return LibraryAddStatus
     */
    public static function success(LibraryEntity $library): LibraryAddStatus {
        return new static($library, self::SUCCESS);
    }

    /**
     * @param LibraryEntity|null $library
     * @param string $message
     */
    public function __construct($library, string $message) {
        $this->library = $library;
        $this->message = $message;
    }
}

class LibraryEntity {

    /** @var string */
    protected $name;

    /** @var string */
    protected $location;

    /**
     * @param string $name
     * @param string $location
     */
    public function __construct(string $name, string $location) {
        $this->name = $name;
        $this->location = rtrim($location, "/ \n\r");
    }

    /**
     * @return string
     */
    public function getName(): string {
        return $this->name;
    }

    /**
     * Get the library container location
     *
     * @return string
     */
    public function getLocation(): string {
        return $this->location;
    }

    /**
     * Checks to see if the library has the specified name
     *
     * @param string $name
     * @return bool
     */
    public function hasNameOf(string $name): bool {
        return $this->getName() === $name;
    }

    /**
     * @param string $location
     * @return bool
     */
    public function hasLocationOf(string $location): bool {
        return $this->location === rtrim($location, "/ \n\r");
    }

    /**
     * @param LibraryEntity $library
     * @return bool
     */
    public function is(LibraryEntity $library): bool {
        return $this->hasNameOf($library->getName()) &&
            $this->hasLocationOf($library->getLocation());
    }

    /**
     * @param LibraryEntity $library
     * @return bool
     */
    public function isNot(LibraryEntity $library): bool {
        return !$this->is($library);
    }

    /**
     * Get the actual library named path
     *
     * @return string
     */
    public function getLibraryPath(): string {
        return $this->location . '/' .  $this->name;
    }
}
