<?php

    namespace Tholabs\I18nBuild\Tokens;

    class KeyDefinition implements Tokenized {

        private string $keyName;
        private string $originalChunk;

        /** @var Tokenized[] */
        private array $bodyTokens;

        function __construct (string $keyName, string $originalChunk, Tokenized ...$bodyTokens) {
            $this->keyName = $keyName;
            $this->originalChunk = $originalChunk;
            $this->bodyTokens = $bodyTokens;
        }

        function getKeyName () : string {
            return $this->keyName;
        }

        function getOriginalChunk () : string {
            return $this->originalChunk;
        }

        function getBodyTokens () : array {
            return $this->bodyTokens;
        }

    }