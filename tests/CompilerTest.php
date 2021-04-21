<?php

    // Overwriting the date function so we always get the same result from our generated code
    namespace Tholabs\I18nBuild\Writer\Nodes;
    function date($format) {
        \date_default_timezone_set('UTC');
        return \date($format, 1629639462);
    }

    namespace Tholabs\I18nBuildTests;

    use PHPUnit\Framework\TestCase;
    use Tholabs\I18nBuild\Compiler;
    use Tholabs\I18nBuild\Exceptions\UnknownTokenException;
    use Tholabs\I18nBuild\Tokens\ChildlessTokenTrait;
    use Tholabs\I18nBuild\Tokens\KeyDefinition;
    use Tholabs\I18nBuild\Tokens\PackageDefinition;
    use Tholabs\I18nBuild\Tokens\Text;
    use Tholabs\I18nBuild\Tokens\Tokenized;
    use Tholabs\I18nBuild\Tokens\Variable;
    use Tholabs\I18nBuild\Writer\NodeWriters;

    class CompilerTest extends TestCase {

        /** @var resource */
        private $stream;
        private Compiler $compiler;

        function setUp () : void {
            $this->stream = fopen('php://temp', 'w+');
            $this->compiler = new Compiler($this->stream, ...NodeWriters::getDefault());
        }

        function testShouldCompilePackage () {
            $this->compiler->compileToken(new PackageDefinition('foo',
                new KeyDefinition('bar', 'I am a test key', new Text('I am a test key')),
                new KeyDefinition('hello.world', 'Hello {name}!', new Text('Hello '), new Variable('name'), new Text('!')),
                new KeyDefinition('with.linefeed', "Hello\n{name}!", new Text("Hello\n"), new Variable('name'), new Text('!')),
                new KeyDefinition('with.doubleQuote', 'Hello "{name}"!', new Text('Hello "'), new Variable('name'), new Text('"!')),
                new KeyDefinition('with.singleQuoteInVariable', "Hello {name'lol}!", new Text("Hello "), new Variable("name'lol"), new Text('!')),
                new KeyDefinition('with.linefeedInVariable', "Hello {name\nlol}!", new Text("Hello "), new Variable('name\'."\n".\'lol'), new Text('!')),
                new KeyDefinition('with.variableToken', "Hello \$name!", new Text("Hello \$name!")),
            ));

            $this->assertSame(<<<'ExpectedRendering'
                              <?php
                              /**
                               * This file has been automatically created using
                               * https://github.com/filecage/i18n-build
                               *
                               * You should not modify it.
                               *
                               * @i18n-package foo
                               * @i18n-version 2.0.0
                               * @createdAt 2021-08-22T13:37:42+00:00
                               */
                              
                              return [
                                  'meta' => [
                                      'i18n-version' => [2, 0, 0]
                                  ],
                                  'keys' => [
                                      'bar' => 'I am a test key',
                                      'hello.world' => [fn(array $context) => "Hello " . ($context['name'] ?? '') . "!", 'Hello {name}!'],
                                      'with.linefeed' => [fn(array $context) => "Hello\n" . ($context['name'] ?? '') . "!", 'Hello'."\n".'{name}!'],
                                      'with.doubleQuote' => [fn(array $context) => "Hello \"" . ($context['name'] ?? '') . "\"!", 'Hello "{name}"!'],
                                      'with.singleQuoteInVariable' => [fn(array $context) => "Hello " . ($context['name\'lol'] ?? '') . "!", 'Hello {name\'lol}!'],
                                      'with.linefeedInVariable' => [fn(array $context) => "Hello " . ($context['name\'."\n".\'lol'] ?? '') . "!", 'Hello {name'."\n".'lol}!'],
                                      'with.variableToken' => 'Hello $name!',
                                  ]
                              ];
                              
                              ExpectedRendering
            , stream_get_contents($this->stream, -1, 0));
        }

        function testExpectsCompilerToThrowUnknownTokenException () {
            $this->expectException(UnknownTokenException::class);
            $this->expectExceptionMessage('Compiler exception: Unknown token can not be compiled: Tholabs\I18nBuildTests\FooToken');

            $this->compiler->compileToken(new FooToken());
        }

        function testExpectsCompilerToThrowInvalidArgumentExceptionForInvalidStreams () {
            $this->expectException(\InvalidArgumentException::class);
            $this->expectExceptionMessage('Given `$stream` must be a writable resource');

            /** @noinspection PhpParamsInspection */
            new Compiler('foo');
        }

    }

    class FooToken implements Tokenized {use ChildlessTokenTrait;}