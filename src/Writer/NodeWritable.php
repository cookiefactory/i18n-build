<?php

    namespace Tholabs\I18nBuild\Writer;

    use Tholabs\I18nBuild\Compiler;
    use Tholabs\I18nBuild\Exceptions\InvalidTokenException;
    use Tholabs\I18nBuild\Tokens\Tokenized;

    interface NodeWritable {

        /**
         * Determines whether a `NodeWritable` is applicable for a given `Tokenized`
         *
         * @param Tokenized $token
         * @return bool
         */
        function isApplicableFor (Tokenized $token) : bool;

        /**
         * Compiles the Token to the actual asset code
         *
         * @param Compiler $compiler
         * @param Tokenized $token
         * @return string
         * @throws InvalidTokenException If `$token` is not applicable for this Writer
         */
        function compile (Compiler $compiler, Tokenized $token) : string;

    }