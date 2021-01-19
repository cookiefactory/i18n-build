<?php

    namespace Tholabs\I18nBuild\Writer\Nodes;

    use Tholabs\I18nBuild\Compiler;
    use Tholabs\I18nBuild\Exceptions\InvalidTokenException;
    use Tholabs\I18nBuild\Tokens\KeyDefinition;
    use Tholabs\I18nBuild\Tokens\Tokenized;
    use Tholabs\I18nBuild\Writer\NodeWritable;
    use Tholabs\I18nBuild\Writer\SubCompilerTrait;
    use Tholabs\I18nBuild\Writer\TokenTypeAssertionTrait;

    class KeyDefinitionNodeWriter implements NodeWritable {
        use SubCompilerTrait, TokenTypeAssertionTrait;

        function isApplicableFor (Tokenized $token) : bool {
            return $token instanceof KeyDefinition;
        }

        /**
         * @param Compiler $compiler
         * @param Tokenized|KeyDefinition $token
         * @return string
         * @throws InvalidTokenException
         */
        function compile (Compiler $compiler, Tokenized $token) : string {
            $this->assertTokenType(KeyDefinition::class, $token);

            $keyBody = '';
            $subCompiler = $compiler->withIndentReset();
            foreach ($token->getBodyTokens() as $bodyToken) {
                $keyBody .= rtrim($this->subCompile(true, $subCompiler, $bodyToken), PHP_EOL);
            }

            return "'{$token->getKeyName()}' => fn(array \$context) => \"{$keyBody}\",";
        }

    }