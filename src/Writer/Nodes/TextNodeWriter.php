<?php

    namespace Tholabs\I18nBuild\Writer\Nodes;

    use Tholabs\I18nBuild\Compiler;
    use Tholabs\I18nBuild\Exceptions\InvalidTokenException;
    use Tholabs\I18nBuild\Tokens\Text;
    use Tholabs\I18nBuild\Tokens\Tokenized;
    use Tholabs\I18nBuild\Writer\NodeWritable;
    use Tholabs\I18nBuild\Writer\SafeEscapingTrait;
    use Tholabs\I18nBuild\Writer\SubCompilerTrait;
    use Tholabs\I18nBuild\Writer\TokenTypeAssertionTrait;

    class TextNodeWriter implements NodeWritable {
        use SubCompilerTrait, TokenTypeAssertionTrait, SafeEscapingTrait;

        function isApplicableFor (Tokenized $token) : bool {
            return $token instanceof Text;
        }

        /**
         * @param Compiler $compiler
         * @param Tokenized|Text $token
         * @return string
         * @throws InvalidTokenException
         */
        function compile (Compiler $compiler, Tokenized $token) : string {
            $this->assertTokenType(Text::class, $token);

            return "{$this->escapeStringForDoubleQuoteUsage($token->getBody())}";
        }

    }