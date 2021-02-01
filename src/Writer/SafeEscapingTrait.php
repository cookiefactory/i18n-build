<?php

    namespace Tholabs\I18nBuild\Writer;

    /**
     * @internal
     */
    trait SafeEscapingTrait {

        function escapeStringForDoubleQuoteUsage (string $input) : string {
            $input = $this->normalizeLinebreaks($input);
            $input = str_replace(["\n", '"', '$'], ['\n', '\"', '\$'], $input);

            return $input;
        }

        function escapeStringForSingleQuoteUsage (string $input) : string {
            $input = $this->normalizeLinebreaks($input);
            $input = str_replace("'", "\'", $input);

            // When multiple linebreaks occur we want to put them together to keep the generated code cleaner
            $input = preg_replace_callback('/([\n]+)/', fn(array $match) => sprintf('\'."%s".\'', str_replace("\n", '\n', $match[1])), $input);

            return $input;
        }

        private function normalizeLinebreaks (string $input) : string {
            return str_replace(["\r\n", "\r"], "\n", $input);
        }

    }