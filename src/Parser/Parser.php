<?php

    namespace Tholabs\I18nBuild\Parser;

    use Tholabs\I18nBuild\Exceptions\ParserException;
    use Tholabs\I18nBuild\Tokens\Tokenized;

    interface Parser {

        /**
         * Tokenizes a given chunk
         *
         * @param Tokenized|null $parentToken
         * @param string $chunk
         * @return Tokenized
         * @throws ParserException
         */
        function tokenize (?Tokenized $parentToken, string $chunk) : Tokenized;

    }