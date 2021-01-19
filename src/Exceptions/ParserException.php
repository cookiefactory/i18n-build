<?php

    namespace Tholabs\I18nBuild\Exceptions;

    use Throwable;

    class ParserException extends \Exception {
        function __construct ($message, $code = 0, Throwable $previous = null) {
            parent::__construct("Parser exception: {$message}", $code, $previous);
        }
    }