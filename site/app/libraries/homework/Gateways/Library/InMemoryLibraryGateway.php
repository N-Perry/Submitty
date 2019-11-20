<?php namespace app\libraries\homework\Gateways\Library;

use app\libraries\homework\Entities\LibraryEntity;
use app\libraries\homework\Gateways\LibraryGateway;
use app\libraries\homework\Entities\LibraryAddStatus;

class InMemoryLibraryGateway implements LibraryGateway {
    /** @var LibraryEntity[] */
    protected $libraries = [];

    public function addLibrary(LibraryEntity $library): LibraryAddStatus {
        if ($this->libraryExists($library)) {
            return LibraryAddStatus::error('Library already exists');
        }

        $this->libraries[] = $library;

        return LibraryAddStatus::success($library);
    }

    /** @inheritDoc */
    public function addGitLibrary(LibraryEntity $library, string $repoUrl): LibraryAddStatus {
        return $this->addLibrary($library);
    }

    /** @inheritDoc */
    public function addZipLibrary(LibraryEntity $library, string $tmpFilePath): LibraryAddStatus {
        return $this->addLibrary($library);
    }

    /** @inheritDoc */
    public function getAllLibraries(string $location): array {
        $results = [];

        /** @var LibraryEntity $library */
        foreach ($this->libraries as $library) {
            if ($library->hasLocationOf($location)) {
                $results[] = $library;
            }
        }

        return $results;
    }

    /** @inheritDoc */
    public function libraryExists(LibraryEntity $library): bool {
        return count(array_filter($this->libraries, function (LibraryEntity $item) use ($library) {
            return $item->is($library);
        })) > 0;
    }

    /** @inheritDoc */
    public function removeLibrary(LibraryEntity $library): bool {
        if ($library->hasNameOf('fail to remove')) {
            return false;
        }

        $this->libraries = array_filter($this->libraries, function (LibraryEntity $storedLib) use ($library) {
            return $storedLib->isNot($library);
        });

        return true;
    }
}
