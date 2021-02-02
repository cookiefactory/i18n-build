<?php

    namespace Tholabs\I18nBuild\Parser\KeyBodies;

    use Tholabs\I18nBuild\Exceptions\ParserException;
    use Tholabs\I18nBuild\Parser\Parser;
    use Tholabs\I18nBuild\Parser\SeekableString;
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
            $parentToken = $parentToken ?? new KeyDefinition('', $chunk);

            if ($chunk !== '' && strstr($parentToken->getOriginalChunk(), $chunk) === false) {
                throw new ParserException("Input chunk is not part of parent token original chunk for key `{$parentToken->getKeyName()}`");
            }

            try {
                $tokens = iterator_to_array($this->loopSeek(new SeekableString($chunk), static::CURLY_START, static::CURLY_END), false);
            } catch (ParserException $exception) {
                throw new ParserException("{$exception->getInnerMessage()} for key `{$parentToken->getKeyName()}`", $exception->getCode(), $exception);
            }

            return new KeyDefinition($parentToken->getKeyName(), $parentToken->getOriginalChunk(), ...$parentToken->getBodyTokens(), ...$tokens);
        }

        /**
         * @param SeekableString $seeker
         * @param string $needleStart
         * @param string $needleEnd
         * @return \Generator|Tokenized[]
         * @throws ParserException
         */
        private function loopSeek (SeekableString $seeker, string $needleStart, string $needleEnd) : \Generator {
            $textBody = $seeker->seek($needleStart);
            if (!empty($textBody)) {
                yield new Text($textBody);
            }

            if (!$seeker->eof()) {
                $variableName = $seeker->seek($needleEnd, SeekableString::SEEK_ASSERT_TOUCH);

                if (!empty($variableName)) {
                    yield new Variable($variableName);
                } elseif ($seeker->eof()) {
                    // If we're at EOF and have no variable name, the closing tag is missing
                    throw new ParserException('Expected variable closing tag but encountered EOF instead in key definition');
                }
            }

            // If there is still contents to be consumed, move forward
            if (!$seeker->eof()) {
                yield from $this->loopSeek($seeker, $needleStart, $needleEnd);
            }
        }


    }