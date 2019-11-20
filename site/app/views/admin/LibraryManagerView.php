<?php

namespace app\views\admin;

use app\libraries\FileUtils;
use app\views\AbstractView;

class LibraryManagerView extends AbstractView {
    public function showLibraryManager(String $text, array $libraries) {
        $this->core->getOutput()->addBreadcrumb('Manage');
        $this->core->getOutput()->addInternalCss('admin-gradeable.css');
        $this->core->getOutput()->addInternalJs('drag-and-drop.js');
        $this->core->getOutput()->addInternalCss(FileUtils::joinPaths('fileinput.css'));
        return $this->core->getOutput()->renderTwigTemplate("admin/library/LibraryManager.twig", [
            'text' => $text,
            'libraries' => $libraries,
            'git_submit_url' => $this->core->buildUrl(['homework/library', 'manage', 'upload', 'git']),
            'filepath_submit_url' => $this->core->buildUrl(['homework/library', 'manage', 'upload', 'filepath']),
            'zip_submit_url' => $this->core->buildUrl(['homework/library', 'manage', 'upload', 'zip']),
            "csrf_token" => $this->core->getCsrfToken()
        ]);
    }
}
