<?php

    namespace Tholabs\I18nBuild\Parser\KeyBodies;

    use Tholabs\I18nBuild\Exceptions\ParserException;
    use Tholabs\I18nBuild\Parser\Parser;
    use Tholabs\I18nBuild\Tokens\KeyDefinition;
    use Tholabs\I18nBuild\Tokens\Text;
    use Tholabs\I18nBuild\Tokens\Tokenized;
    use Tholabs\I18nBuild\Tokens\Variable;

    /**
     * This parser parses key bodies with variables wrapped in curly braces, e.g.
     *
     *      `Hello {name}!`
     *
     * will be parsed to
     *
     *      Key Token (
     *          Text Token (Hello ),
     *          Variable Token (name),
     *          Text Token (!)
     *      )
     */
    class CurlyBracesKeyParser implements Parser {
        protected const CURLY_START = '{';
        protected const CURLY_END = '}';

        function tokenize (?Tokenized $parentToken, string $chunk) : Tokenized {
            $parentToken = $parentToken ?? new KeyDefinition('');

            // Create a stream and move it to the beginning
            $chunkStream = fopen('php://temp', 'w+');
            fputs($chunkStream, $chunk);
            rewind($chunkStream);

            $tokens = iterator_to_array($this->loopSeek($chunkStream, static::CURLY_START, static::CURLY_END));
            fclose($chunkStream);

            return new KeyDefinition($parentToken->getKeyName(), ...$parentToken->getBodyTokens(), ...$tokens);
        }

        /**
         * @param $stream
         * @param string $needleStart
         * @param string $needleEnd
         * @return \Generator|Tokenized[]
         * @throws ParserException
         */
        private function loopSeek ($stream, string $needleStart, string $needleEnd) : \Generator {
            $textBody = $this->seek($stream, $needleStart);
            if (!empty($textBody)) {
                yield new Text($textBody);
            }

            if (!feof($stream)) {
                $variableName = $this->seek($stream, $needleEnd);

                // If the stream is at its end, check if the ending tag was present or throw an exception if it wasn't
                if (feof($stream)) {
                    $needleEndLength = strlen($needleEnd);
                    fseek($stream, fstat($stream)['size'] - $needleEndLength);

                    if (fread($stream, $needleEndLength) !== $needleEnd) {
                        throw new ParserException('Expected variable closing tag but encountered EOF in key definition');
                    }
                }

                if (!empty($variableName)) {
                    yield new Variable($variableName);
                }
            }

            if (!feof($stream)) {
                yield from $this->loopSeek($stream, $needleStart, $needleEnd);
            }
        }

        /**
         * Reads the stream until `$needle` is found; leaves the stream at the offset BEFORE `$needle`
         * Returns the bytes that have been buffered before the encounter
         *
         * @param resource $stream
         * @param string $needle
         * @return string
         */
        private function seek ($stream, string $needle) : string {
            // `feof()` only matches if we're past EOF, which does not work when resetting the pointer on every iteration
            // so instead check if we've read the full length
            $eofOffset = fstat($stream)['size'];
            $needleByteCount = strlen($needle);
            $buffer = '';

            while (ftell($stream) !== $eofOffset && fread($stream, $needleByteCount) !== $needle) {
                // Reset the stream to where it was before we tried to read the starting delimiter
                fseek($stream, ftell($stream) - $needleByteCount);
                $buffer .= fread($stream, 1);
            }

            // If we've read the full size of the stream, move the pointer one step further so that `feof()` returns true
            if (ftell($stream) === $eofOffset) {
                fread($stream, 1);
            }

            return $buffer;
        }


    }