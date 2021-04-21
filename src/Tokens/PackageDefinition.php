<?php

    namespace Tholabs\I18nBuild\Tokens;

    class PackageDefinition implements Tokenized {
        use SeekableTokenTrait;

        private string $packageName;

        /** @var KeyDefinition[] */
        private array $keys;

        function __construct (string $packageName, KeyDefinition ...$keys) {
            $this->packageName = $packageName;
            $this->keys = $keys;
        }

        function getChildren () : iterable {
            return $this->keys;
        }

        /**
         * @return string
         */
        function getPackageName () : string {
            return $this->packageName;
        }

        /**
         * @return KeyDefinition[]
         */
        function getKeys () : array {
            return $this->keys;
        }

    }