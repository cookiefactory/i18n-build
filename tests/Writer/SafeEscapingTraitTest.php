<?php

    namespace Tholabs\I18nBuildTests\Writers;

    use PHPUnit\Framework\TestCase;
    use Tholabs\I18nBuild\Writer\SafeEscapingTrait;

    class SafeEscapingTraitTest extends TestCase {

        function provideDangerousInputs () {
            return [
                'double quote and single quote usage' => [
                    "This is \"a problem\" with me being here 'for you'",
                    <<<'DOUBLEQUOTED'
                    This is \"a problem\" with me being here 'for you'
                    DOUBLEQUOTED,
                    <<<'SINGLEQUOTED'
                    This is "a problem" with me being here \'for you\'
                    SINGLEQUOTED
                ],

                'different line break bytes' => [
                    "This is\r\na problem with me\nbeing here\rfor you",
                    <<<'DOUBLEQUOTED'
                    This is\na problem with me\nbeing here\nfor you
                    DOUBLEQUOTED,
                    <<<'SINGLEQUOTED'
                    This is'."\n".'a problem with me'."\n".'being here'."\n".'for you
                    SINGLEQUOTED
                ],

                'multiple line feeds with blank space in between' => [
                    "This is a problem with me\n\n\nbeing here for you",
                    <<<'DOUBLEQUOTED'
                    This is a problem with me\n\n\nbeing here for you
                    DOUBLEQUOTED,
                    <<<'SINGLEQUOTED'
                    This is a problem with me'."\n\n\n".'being here for you
                    SINGLEQUOTED
                ],

                'with variables' => [
                    "This is a problem with {\$me} being here for \$you",
                    <<<'DOUBLEQUOTED'
                    This is a problem with {\$me} being here for \$you
                    DOUBLEQUOTED,
                    <<<'SINGLEQUOTED'
                    This is a problem with {$me} being here for $you
                    SINGLEQUOTED
                ]
            ];
        }


        /**
         * @param string $input
         * @param string $expectedDoubleQuoteOutput
         * @param string $expectedSingleQuoteOutput
         *
         * @dataProvider provideDangerousInputs
         */
        function testShouldCorrectlyEscapeDangerousStrings (string $input, string $expectedDoubleQuoteOutput, string $expectedSingleQuoteOutput) {
            $this->assertSame($expectedDoubleQuoteOutput, $this->escaper->escapeForDoubleQuote($input));
            $this->assertSame($expectedSingleQuoteOutput, $this->escaper->escapeForSingleQuote($input));
        }

        private $escaper;
        function setUp () : void {
            $this->escaper = new class {
                use SafeEscapingTrait;

                function escapeForDoubleQuote (string $input) : string {
                    return $this->escapeStringForDoubleQuoteUsage($input);
                }

                function escapeForSingleQuote (string $input) : string {
                    return $this->escapeStringForSingleQuoteUsage($input);
                }

            };
        }


    }