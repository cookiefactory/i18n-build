<?php

    namespace Tholabs\I18nBuild\Writer;

    use Tholabs\I18nBuild\Exceptions\InvalidTokenException;
    use Tholabs\I18nBuild\Tokens\Tokenized;

    trait TokenTypeAssertionTrait {

        /**
         * @param string $expectedTokenTypeClassName
         * @param Tokenized $actualToken
         * @throws InvalidTokenException
         */
        private function assertTokenType (string $expectedTokenTypeClassName, Tokenized $actualToken) : void {
            if (!$actualToken instanceof $expectedTokenTypeClassName) {
                throw new InvalidTokenException($expectedTokenTypeClassName, $actualToken);
            }
        }

    }