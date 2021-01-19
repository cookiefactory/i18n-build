<?php

    namespace Tholabs\I18nBuild\Writer;

    use Tholabs\I18nBuild\Compiler;
    use Tholabs\I18nBuild\Exceptions\InvalidTokenException;
    use Tholabs\I18nBuild\Exceptions\UnknownTokenException;
    use Tholabs\I18nBuild\Tokens\Tokenized;

    trait SubCompilerTrait {

        /**
         * @param bool $keepNonPrintableCharacters
         * @param Compiler $compiler
         * @param Tokenized ...$tokens
         * @return string
         * @throws InvalidTokenException
         * @throws UnknownTokenException
         */
        private function subCompile (bool $keepNonPrintableCharacters, Compiler $compiler, Tokenized ...$tokens) : string {
            $payload = fopen('php://temp', 'w+');
            $subCompiler = $compiler->withStream($payload);

            foreach ($tokens as $token) {
                $subCompiler->compileToken($token);
            }

            $subcompiled = stream_get_contents($payload, -1, 0);
            fclose($payload);

            if ($keepNonPrintableCharacters === false) {
                $subcompiled = trim($subcompiled);
            }

            return $subcompiled;
        }

    }