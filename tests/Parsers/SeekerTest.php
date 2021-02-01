<?php

    namespace Tholabs\I18nBuildTests\Parsers;

    use PHPUnit\Framework\TestCase;
    use Tholabs\I18nBuild\Parser\SeekableString;

    class SeekerTest extends TestCase {

        /**
         * @param string $haystack
         * @param string $needle
         * @param string ...$expectedResults
         *
         * @dataProvider provideSimpleNeedleHaystackPairs
         */
        function testShouldSeekForSimpleNeedle (string $haystack, string $needle, string ...$expectedResults) {
            $seeker = new SeekableString($haystack);

            $actualResults = [];
            while ($seeker->eof() === false) {
                $actualResults[] = $seeker->seek($needle);
            }

            $this->assertSame($expectedResults, $actualResults);
        }

        function provideSimpleNeedleHaystackPairs () : array {
            return [
                'no needle in haystack' => ['Hello My Friend', '!', 'Hello My Friend'],
                'one single-character needle' => ['Hello ! My Friend', '!', 'Hello ', ' My Friend'],
                'multiple single-character needles' => ['Hello ! My Friend ! Lol', '!', 'Hello ', ' My Friend ', ' Lol'],
                'multiple multi-character needles' => ['Hello !! My Friend !! Lol', '!!', 'Hello ', ' My Friend ', ' Lol'],
            ];
        }

    }