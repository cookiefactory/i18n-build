<?php

    namespace Tholabs\I18nBuild\Exceptions;

    use Throwable;

    class CompilerException extends \Exception {
        function __construct ($message, $code = 0, Throwable $previous = null) {
            parent::__construct("Compiler exception: {$message}", $code, $previous);
        }
    }