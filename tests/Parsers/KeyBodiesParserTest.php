<?php

    namespace Tholabs\I18nBuildTests\Parsers;

    use PHPUnit\Framework\TestCase;
    use Tholabs\I18nBuild\Exceptions\ParserException;
    use Tholabs\I18nBuild\Parser\KeyBodies\CurlyBracesKeyParser;
    use Tholabs\I18nBuild\Parser\Parser;
    use Tholabs\I18nBuild\Tokens\KeyDefinition;
    use Tholabs\I18nBuild\Tokens\Text;
    use Tholabs\I18nBuild\Tokens\Variable;

    class KeyBodiesParserTest extends TestCase {

        /**
         * @param array $expectedKeyBodyTokens
         * @param string $inputChunk
         * @param Parser $parser
         * @throws ParserException
         * @dataProvider provideSimpleKeysToParse
         */
        function testShouldParseSimpleKeyStructs (array $expectedKeyBodyTokens, string $inputChunk, Parser $parser) {
            /** @var KeyDefinition $token */
            $token = $parser->tokenize(new KeyDefinition('bodiesParserTest', $inputChunk . ' Test'), $inputChunk);

            $this->assertInstanceOf(KeyDefinition::class, $token);
            $this->assertSame('bodiesParserTest', $token->getKeyName(), 'KeyBody parser may not change key name');
            $this->assertSame($inputChunk . ' Test', $token->getOriginalChunk(), 'KeyBody parser may not change original chunk value');

            $this->assertCount(count($expectedKeyBodyTokens), $token->getBodyTokens());
            foreach ($expectedKeyBodyTokens as $i => $expectedToken) {
                $this->assertEquals($expectedToken, $token->getBodyTokens()[$i]);
            }
        }

        function provideSimpleKeysToParse () : \Generator {
            $expectedKeyBodyTokens = [
                'variable in between' => [
                    new Text('Hello '),
                    new Variable('name'),
                    new Text('!')
                ],
                'variable at the start' => [
                    new Variable('name'),
                    new Text(', welcome back!')
                ],
                'variable at the end' => [
                    new Text('Hello '),
                    new Variable('name'),
                ],
            ];

            foreach ($this->provideSimpleHelloNameKeyParsers() as $parserName => [$parser, $templates, ]) {
                $i = 0;
                foreach ($expectedKeyBodyTokens as $testCaseName => $expectedKeyBodyToken) {
                    yield $parserName . ', ' . $testCaseName => [$expectedKeyBodyToken, $templates[$i++], $parser];
                }
            }
        }

        function provideSimpleHelloNameKeyParsers () {
            return [
                'curly braces parser' => [
                    new CurlyBracesKeyParser(),
                    ['Hello {name}!', '{name}, welcome back!', 'Hello {name}'],
                    ['{name is a good name', 'Hello {name']
                ]
            ];
        }

        /**
         * @param Parser $parser
         * @throws ParserException
         * @dataProvider provideKeyParsers
         */
        function testShouldParseKeyWithoutVariable (Parser $parser) {
            /** @var KeyDefinition $token */
            $token = $parser->tokenize(new KeyDefinition('bodiesParserTest', 'Hello World! Test'), 'Hello World!');

            $this->assertInstanceOf(KeyDefinition::class, $token);
            $this->assertSame('bodiesParserTest', $token->getKeyName(), 'KeyBody parser may not change key name');
            $this->assertSame('Hello World! Test', $token->getOriginalChunk(), 'KeyBody parser may not change original chunk value');

            $expectedKeyBodyTokens = [
                new Text('Hello World!'),
            ];

            foreach ($expectedKeyBodyTokens as $i => $expectedToken) {
                $this->assertEquals($expectedToken, $token->getBodyTokens()[$i]);
            }
        }

        function provideKeyParsers () {
            foreach ($this->provideSimpleHelloNameKeyParsers() as $description => [$parser]) {
                yield $description => [$parser];
            }
        }

        /**
         * @param Parser $parser
         * @param string $brokenTemplate
         * @throws ParserException
         * @dataProvider provideBrokenVariableDefinitions
         */
        function testShouldThrowExceptionForBrokenVariableDefinitions (Parser $parser, string $brokenTemplate) {
            $this->expectException(ParserException::class);
            $this->expectExceptionMessage('Parser exception: Expected variable closing tag but encountered EOF instead in key definition');

            $parser->tokenize(null, $brokenTemplate);
        }

        function provideBrokenVariableDefinitions () {
            foreach ($this->provideSimpleHelloNameKeyParsers() as $parserName => [$parser, $templates, $brokenTemplates]) {
                foreach ($brokenTemplates as $name => $brokenTemplate) {
                     yield $parserName . ', ' . $name => [$parser, $brokenTemplate];
                }
            }
        }

        /**
         * @param Parser $parser
         * @throws ParserException
         * @dataProvider provideKeyParsers
         */
        function testShouldThrowExceptionWhenPassingAChunkThatIsNotContainedInTheParent (Parser $parser) {
            $this->expectException(ParserException::class);
            $this->expectExceptionMessage('Parser exception: Input chunk is not part of parent token original chunk');

            $parser->tokenize(new KeyDefinition('broken', 'Oh Lord Throw An Exception Here'), 'exception');
        }

    }