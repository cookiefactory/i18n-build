<?php

    namespace Tholabs\I18nBuild;

    use Tholabs\I18nBuild\Exceptions\InvalidTokenException;
    use Tholabs\I18nBuild\Exceptions\UnknownTokenException;
    use Tholabs\I18nBuild\Writer\NodeWritable;
    use Tholabs\I18nBuild\Tokens\Tokenized;

    class Compiler {

        /** @var resource */
        private $stream;

        /** @var NodeWritable[] */
        private array $nodeWritables;

        /** @var int */
        private $indent = 0;

        /**
         * @param resource $stream
         * @param NodeWritable ...$nodeWritables
         */
        function __construct ($stream, NodeWritable ...$nodeWritables) {
            $this->assertWritableResource($stream);
            $this->stream = $stream;
            $this->nodeWritables = $nodeWritables;
        }

        /**
         * Compiles the Token using the registered `NodeWriteable` instances
         * Compiler output will be written to the given stream
         *
         * @param Tokenized $token
         * @throws UnknownTokenException
         * @throws InvalidTokenException
         */
        function compileToken (Tokenized $token) : void {
            foreach ($this->nodeWritables as $nodeWritable) {
                if ($nodeWritable->isApplicableFor($token)) {
                    foreach ($this->prepareRaw($nodeWritable->compile($this, $token)) as $line) {
                        fwrite($this->stream, $line);
                    }

                    return; // only one writer per token
                }
            }

            throw new UnknownTokenException($token);
        }

        /**
         * Can be used to create the same compiler instance but with a fresh stream
         *
         * @param $stream
         * @return $this
         */
        function withStream ($stream) : self {
            $this->assertWritableResource($stream);
            $clone = clone $this;
            $clone->stream = $stream;

            return $clone;
        }

        /**
         * Increases the indent of the compiler for all future lines to be written
         * Note that this method does not provide full immutability as the underlying
         * stream will not be cloned
         *
         * @return $this
         */
        function withIndentIncreased () : self {
            $clone = clone $this;
            $clone->indent++;

            return $clone;
        }

        /**
         * Resets the indent of the compiler for all future lines to be written
         * Note that this method does not provide full immutability as the underlying
         * stream will not be cloned
         *
         * @return $this
         */
        function withIndentReset () : self {
            $clone = clone $this;
            $clone->indent = 0;

            return $clone;
        }

        /**
         * @param string $raw
         * @return \Iterator|string[]
         */
        private function prepareRaw (string $raw) : \Iterator {
            // Normalize line endings and split by line to re-assemble them with the system's default EOL
            $raw = str_replace("\r\n", "\n", $raw);
            $raw = str_replace("\r", "\n", $raw);
            $lines = explode("\n", $raw);

            foreach ($lines as $line) {
                yield str_repeat('    ', $this->indent) . $line . PHP_EOL;
            }
        }

        /**
         * @param resource $stream
         */
        private function assertWritableResource ($stream) : void {
            if (is_resource($stream) === false) {
                throw new \InvalidArgumentException('Given `$stream` must be a writable resource');
            }
        }

    }