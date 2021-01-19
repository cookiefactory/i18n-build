<?php

    namespace Tholabs\I18nBuild\Exceptions;

    use Tholabs\I18nBuild\Tokens\Tokenized;

    class UnknownTokenException extends CompilerException {
        function __construct (Tokenized $token) {
            $tokenClassName = get_class($token);
            parent::__construct("Unknown token can not be compiled: {$tokenClassName}");
        }
    }