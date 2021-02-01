<?php

    namespace Tholabs\I18nBuild\Parser;

    /**
     * This class can be used to seek for a particular string inside other strings
     * Seeking will return all characters of `$haystack` until the next occurrence of `$needle`. The needle itself will
     * be skipped.
     *
     */
    class SeekableString {
        const SEEK_ASSERT_TOUCH = 0b1;

        private string $haystack;

        /** @var resource */
        private $buffer;

        function __construct (string $haystack) {
            $this->haystack = $haystack;
            $this->buffer = fopen('php://temp', 'w+');

            fputs($this->buffer, $haystack);
            rewind($this->buffer);
        }

        /**
         * Different options can be passed as bitmask to `$options`:
         *
         * `SEEK_ASSERT_TOUCH`: the method will only return the string if the needle was found, otherwise `null`
         *
         * @param string $needle
         * @param int $options
         * @return string|null
         */
        function seek (string $needle, int $options = 0) : ?string {
            $buffer = '';
            $stream = $this->buffer;
            $needleLength = strlen($needle);
            $pointer = ftell($stream);
            $untouched = true;

            while (!feof($stream) && ($untouched = fread($stream, $needleLength) !== $needle)) {
                // Reset the stream to where it was before we tried to read the starting delimiter
                fseek($stream, $pointer);
                $buffer .= fread($stream, 1);

                $pointer++;
            }

            if ($untouched === true && $options & self::SEEK_ASSERT_TOUCH) {
                return null;
            }

            return $buffer;
        }

        /**
         * Returns whether
         * @return bool
         */
        function eof () : bool {
            return feof($this->buffer);
        }

        function __destruct () {
            fclose($this->buffer);
        }

    }