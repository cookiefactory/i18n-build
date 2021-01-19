<?php

    namespace Tholabs\I18nBuild\Writer\Nodes;

    use Tholabs\I18nBuild\Compiler;
    use Tholabs\I18nBuild\Exceptions\InvalidTokenException;
    use Tholabs\I18nBuild\Tokens\Tokenized;
    use Tholabs\I18nBuild\Tokens\Variable;
    use Tholabs\I18nBuild\Writer\NodeWritable;
    use Tholabs\I18nBuild\Writer\SubCompilerTrait;
    use Tholabs\I18nBuild\Writer\TokenTypeAssertionTrait;

    class VariableNodeWriter implements NodeWritable {
        use SubCompilerTrait, TokenTypeAssertionTrait;

        function isApplicableFor (Tokenized $token) : bool {
            return $token instanceof Variable;
        }

        /**
         * @param Compiler $compiler
         * @param Tokenized|Variable $token
         * @return string
         * @throws InvalidTokenException
         */
        function compile (Compiler $compiler, Tokenized $token) : string {
            $this->assertTokenType(Variable::class, $token);

            return "{\$context['{$this->escapeTokenBody($token->getVariableName())}']}";
        }

        private function escapeTokenBody (string $value) : string {
            return addslashes($value);
        }

    }