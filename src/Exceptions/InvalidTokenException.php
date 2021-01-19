<?php

    namespace Tholabs\I18nBuild\Exceptions;

    use Tholabs\I18nBuild\Tokens\Tokenized;

    class InvalidTokenException extends CompilerException {

        function __construct (string $expectedToken, Tokenized $actualToken) {
            $actualTokenClassName = get_class($actualToken);
            parent::__construct("Invalid Token given to NodeWriter: expected `{$expectedToken}` but got `{$actualTokenClassName}` instead");
        }

    }