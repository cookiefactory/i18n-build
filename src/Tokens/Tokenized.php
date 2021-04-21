<?php

    namespace Tholabs\I18nBuild\Tokens;

    /**
     * Describes an entity that has been tokenized, e. g. a variable or a text block
     *
     * @package Tholabs\I18nBuild\Tokens
     */
    interface Tokenized {

        /**
         * Must return an iterable of all child `Tokenized` objects
         *
         * @return iterable
         */
        function getChildren () : iterable;

        /**
         * Checks whether particular child tokens exist inside this token
         *
         * @param string $tokenClassName The needle token class name
         * @param bool $recursive Whether to also look up child tokens
         * @return bool
         *
         * @see SeekableTokenTrait::contains()
         * @see ChildlessTokenTrait::contains()
         */
        function contains (string $tokenClassName, bool $recursive) : bool;

        /**
         * Returns an iterable to iterate over all child `Tokenized` instances of given `tokenClassName`
         *
         * This method must not look up children recursively; if a recursive lookup is desired, it can be
         * implemented in user land by calling the same method on each child.
         *
         * @param string $tokenClassName
         * @return iterable
         *
         * @see SeekableTokenTrait::find()
         * @see ChildlessTokenTrait::find()
         */
        function find (string $tokenClassName) : iterable;

    }