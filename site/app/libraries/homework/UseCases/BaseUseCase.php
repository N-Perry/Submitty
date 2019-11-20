<?php namespace app\libraries\homework\UseCases;


use app\libraries\Core;

abstract class BaseUseCase {
    /** @var Core */
    protected $core;

    /** @var string */
    protected $location;

    public function __construct(Core $core) {
        $this->core = $core;

        $this->location = $this->core->getConfig()->getHomeworkLibraryLocation();
    }

}
