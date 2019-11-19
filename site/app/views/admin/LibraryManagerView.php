<?php

namespace app\views\admin;

use app\libraries\FileUtils;
use app\views\AbstractView;

class LibraryManagerView extends AbstractView {
    public function showLibraryManager(String $text) {
        $this->core->getOutput()->addInternalJs("drag-and-drop.js");
        $this->core->getOutput()->addInternalCss(FileUtils::joinPaths('fileinput.css'));
        return $this->core->getOutput()->renderTwigTemplate("admin/library/LibraryManager.twig", [
            "text" => $text,
            "git_submit_url" => $this->core->buildUrl(['homework/library', 'upload', 'git']),
            "filepath_submit_url" => $this->core->buildUrl(['homework/library', 'upload', 'filepath'])
        ]);
    }
}
