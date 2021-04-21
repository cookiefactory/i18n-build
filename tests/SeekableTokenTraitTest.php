<?php

    namespace Tholabs\I18nBuildTests;

    use PHPUnit\Framework\TestCase;
    use Tholabs\I18nBuild\Tokens\KeyDefinition;
    use Tholabs\I18nBuild\Tokens\SeekableTokenTrait;
    use Tholabs\I18nBuild\Tokens\Text;
    use Tholabs\I18nBuild\Tokens\Tokenized;
    use Tholabs\I18nBuild\Tokens\Variable;

    class SeekableTokenTraitTest extends TestCase {

        private Tokenized $token;

        function setUp () : void {
            parent::setUp();

            $this->token = new class implements Tokenized {
                use SeekableTokenTrait;

                function getChildren () : iterable {
                    return [
                        new Text('Foo'),
                        new Text('Bar'),
                        new KeyDefinition('baz', 'Baz', new Text('Baz'), new Variable('boo'))
                    ];
                }
            };
        }

        function testExpectsTestTokenToContainNeedleTokenNonRecursively () {
            $this->assertTrue($this->token->contains(Text::class, false));
        }

        function testExpectsTestTokenToNotContainNeedleTokenNonRecursively () {
            $this->assertfalse($this->token->contains(Variable::class, false));
        }

        function testExpectsTestTokenToContainNeedleTokenRecursively () {
            $this->assertTrue($this->token->contains(Variable::class, true));
        }

        function testExpectsTestTokenToReturnIteratableOfNeedleTokenOnFirstLevel () {
            $tokens = iterator_to_array($this->token->find(Text::class));
            $expectedTokens = [new Text('Foo'), new Text('Bar')];

            $this->assertCount(2, $tokens);
            foreach ($expectedTokens as $index => $expectedToken) {
                $this->assertEquals($expectedToken, $expectedToken);
            }
         }
    }