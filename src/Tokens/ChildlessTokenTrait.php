<?php

    namespace Tholabs\I18nBuild\Tokens;

    /**
     * This trait can be used for Tokens that do not implement any children
     */
    trait ChildlessTokenTrait {

        /**
         * @return iterable
         *
         * @see Tokenized::getChildren()
         */
        function getChildren () : iterable {
            return [];
        }

        /**
         * @param string $tokenClassName
         * @param bool $recursive
         * @return bool
         *
         * @see Tokenized::contains()
         */
        function contains (string $tokenClassName, bool $recursive) : bool {
            return false;
        }

        /**
         * @param string $tokenClassName
         * @return iterable
         *
         * @see Tokenized::find()
         */
        function find (string $tokenClassName) : iterable {
            return [];
        }

    }