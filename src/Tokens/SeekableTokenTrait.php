<?php

    namespace Tholabs\I18nBuild\Tokens;

    trait SeekableTokenTrait {

        /**
         * Must return an iterable of all child `Tokenized` objects
         *
         * @return iterable
         */
        abstract function getChildren () : iterable;

        /**
         * @param string $tokenClassName
         * @param bool $recursive
         * @return bool
         *
         * @see Tokenized::contains()
         */
        function contains (string $tokenClassName, bool $recursive) : bool {
            foreach ($this->getChildren() as $child) {
                if ($child instanceof $tokenClassName) {
                    return true;
                }

                // TODO: Maybe it has an impact on performance whether we're recursively checking deep-node or the whole layer first?
                if ($recursive === true && $child instanceof Tokenized && $child->contains($tokenClassName, true)) {
                    return true;
                }
            }

            return false;
        }

        /**
         * @param string $tokenClassName
         * @return iterable
         *
         * @see Tokenized::find()
         */
        function find (string $tokenClassName) : iterable {
            foreach ($this->getChildren() as $child) {
                if ($child instanceof $tokenClassName) {
                    yield $child;
                }
            }
        }

    }