<?php

    namespace Tholabs\I18nBuild\Tokens;

    class Variable implements Tokenized {

        private string $variableName;

        function __construct (string $variableName) {
            $this->variableName = $variableName;
        }

        /**
         * @return string
         */
        function getVariableName () : string {
            return $this->variableName;
        }

    }