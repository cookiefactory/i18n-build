<?php

    namespace Tholabs\I18nBuild\Tokens;

    class KeyDefinition implements Tokenized {

        private string $keyName;

        /** @var Tokenized[] */
        private array $bodyTokens;

        function __construct (string $keyName, Tokenized ...$bodyTokens) {
            $this->keyName = $keyName;
            $this->bodyTokens = $bodyTokens;
        }

        function getKeyName () : string {
            return $this->keyName;
        }

        function getBodyTokens () : array {
            return $this->bodyTokens;
        }

    }