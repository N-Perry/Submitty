<?php

namespace app\views;

class HomeworkView extends AbstractView {
	public function library() {
		return $this->core->getOutput()->renderTwigTemplate("HomeworkLibrary.twig", [
			"text" => "TODO: Implement Homework Library Search Page"
		]);
	}
}