<?php

    namespace Tholabs\I18nBuild\Writer\Nodes;

    use Tholabs\I18nBuild\Compiler;
    use Tholabs\I18nBuild\Exceptions\InvalidTokenException;
    use Tholabs\I18nBuild\Exceptions\UnknownTokenException;
    use Tholabs\I18nBuild\Tokens\KeyDefinition;
    use Tholabs\I18nBuild\Tokens\Text;
    use Tholabs\I18nBuild\Tokens\Tokenized;
    use Tholabs\I18nBuild\Writer\NodeWritable;
    use Tholabs\I18nBuild\Writer\SafeEscapingTrait;
    use Tholabs\I18nBuild\Writer\SubCompilerTrait;
    use Tholabs\I18nBuild\Writer\TokenTypeAssertionTrait;

    class KeyDefinitionNodeWriter implements NodeWritable {
        use SubCompilerTrait, TokenTypeAssertionTrait, SafeEscapingTrait;

        function isApplicableFor (Tokenized $token) : bool {
            return $token instanceof KeyDefinition;
        }

        /**
         * @param Compiler $compiler
         * @param Tokenized|KeyDefinition $token
         * @return string
         * @throws InvalidTokenException|UnknownTokenException
         */
        function compile (Compiler $compiler, Tokenized $token) : string {
            $this->assertTokenType(KeyDefinition::class, $token);

            $keyBody = '';
            $subCompiler = $compiler->withIndentReset();
            foreach ($token->getBodyTokens() as $bodyToken) {
                $keyBody .= rtrim($this->subCompile(true, $subCompiler, $bodyToken), PHP_EOL);
            }

            if ($this->containsTextOnly($token)) {
                return $this->renderTextStringOnly($token, $keyBody);
            }

            return $this->render($token, $keyBody);
        }

        private function renderTextStringOnly (KeyDefinition $token, string $keyBody) : string {
            return "'{$token->getKeyName()}' => '{$this->escapeStringForSingleQuoteUsage($token->getOriginalChunk())}',";
        }

        private function render (KeyDefinition $token, string $keyBody) : string {
            return "'{$token->getKeyName()}' => [static fn(array \$context) => \"{$keyBody}\", '{$this->escapeStringForSingleQuoteUsage($token->getOriginalChunk())}'],";
        }

        private function containsTextOnly (Tokenized $tokenized) : bool {
            foreach ($tokenized->getChildren() as $child) {
                if (!$child instanceof Text) {
                    return false;
                }

                if ($child instanceof Tokenized && $this->containsTextOnly($child) === false) {
                    return false;
                }
            }

            return true;
        }

    }