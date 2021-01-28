<?php

    namespace Tholabs\I18nBuild\Writer\Nodes;

    use Tholabs\I18nBuild\Compiler;
    use Tholabs\I18nBuild\Exceptions\InvalidTokenException;
    use Tholabs\I18nBuild\Exceptions\UnknownTokenException;
    use Tholabs\I18nBuild\Tokens\PackageDefinition;
    use Tholabs\I18nBuild\Tokens\Tokenized;
    use Tholabs\I18nBuild\Writer\NodeWritable;
    use Tholabs\I18nBuild\Writer\SubCompilerTrait;
    use Tholabs\I18nBuild\Writer\TokenTypeAssertionTrait;

    class PackageDefinitionNodeWriter implements NodeWritable {
        use SubCompilerTrait, TokenTypeAssertionTrait;

        function isApplicableFor (Tokenized $token) : bool {
            return $token instanceof PackageDefinition;
        }

        /**
         * @param Compiler $compiler
         * @param Tokenized|PackageDefinition $token
         * @return string
         * @throws InvalidTokenException
         * @throws UnknownTokenException
         */
        function compile (Compiler $compiler, Tokenized $token) : string {
            $this->assertTokenType(PackageDefinition::class, $token);
            $creationTimestamp = date('c');

            return  <<<PackageDefinition
                    <?php
                    /**
                     * This file has been automatically created using
                     * https://github.com/filecage/i18n-build
                     *
                     * You should not modify it.
                     *
                     * @i18n-package {$token->getPackageName()}
                     * @i18n-version 1.0.0
                     * @createdAt {$creationTimestamp}
                     */
                    
                    return [
                        'meta' => [
                            'i18n-version' => [1, 0, 0]
                        ],
                        'keys' => [
                            {$this->subCompile(false, $compiler->withIndentIncreased()->withIndentIncreased(), ...$token->getKeys())}
                        ]
                    ];
                    PackageDefinition;
        }
    }