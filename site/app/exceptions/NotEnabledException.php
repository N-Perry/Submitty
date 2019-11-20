<?php declare(strict_types = 1);

namespace app\exceptions;

class NotEnabledException extends BaseException {
    public function __construct($message = 'Feature is not enabled.', $code = 0, $previous = null) {
        parent::__construct($message, [], $code, $previous);
    }
}
