<?php

    namespace Tholabs\I18nBuild\Exceptions;

    use Throwable;

    class ParserException extends \Exception {
        private string $innerMessage;

        function __construct (string $innerMessage, $code = 0, Throwable $previous = null) {
            parent::__construct("Parser exception: {$innerMessage}", $code, $previous);
            $this->innerMessage = $innerMessage;
        }

        function getInnerMessage () : string {
            return $this->innerMessage;
        }
    }