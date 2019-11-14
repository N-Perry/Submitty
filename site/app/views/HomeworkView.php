<?php

namespace app\views;

class HomeworkView extends AbstractView {
	public function library() {
		return $this->core->getOutput()->renderTwigTemplate("HomeworkLibrary.twig", [
			"results" => array(
				"javascript",
				"lisp",
				"php"
			)
		]);
	}

	public function getLorem() {
		// not working because PHP has a weird bug that doesn't allow datasets to store paragraph strings correctly
		$lorems = [
			"Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Amet facilisis magna etiam tempor orci eu. Id aliquet lectus proin nibh nisl. Eu augue ut lectus arcu bibendum at varius vel. Diam maecenas sed enim ut sem viverra.",
			"Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Hac habitasse platea dictumst vestibulum rhoncus est pellentesque. Nulla pharetra diam sit amet nisl suscipit adipiscing bibendum. Diam phasellus vestibulum lorem sed risus ultricies tristique nulla aliquet. Non consectetur a erat nam at lectus.",
			"Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Imperdiet dui accumsan sit amet nulla facilisi morbi. Elementum tempus egestas sed sed risus pretium quam vulputate. Ut morbi tincidunt augue interdum velit euismod in. Id diam vel quam elementum pulvinar.",
		];

		$index = array_rand($lorems);

		return $lorems[$index];
	}
}