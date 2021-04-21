# Changelog
All notable changes to this project will be documented in this file.

The format is based on [Keep a Changelog](https://keepachangelog.com/en/1.0.0/),
and this project adheres to [Semantic Versioning](https://semver.org/spec/v2.0.0.html).

## [Unreleased]
### Added
- `getChildren()`, `find()` and `contains()` have been added to [`Tokenized`](src/Tokens/Tokenized.php) interface
- [`SeekableTokenTrait`](src/Tokens/SeekableTokenTrait.php) and [`ChildlessTokenTrait`](src/Tokens/ChildlessTokenTrait.php) have been added to implement `find()` and `contains()`

### Changed
- The standard compiler output version has been raised to `2.0.0`: If all child `Token` instances of a key are just `Text`, then the compile output will no longer be closure but just a string instead 

## [1.0.0-alpha5] - 2020-02-05
### Fixed
- Compiled PHP assets now use the null coalescing operator to avoid accessing undefined index if context variable is missing 

## [1.0.0-alpha4] - 2020-02-02
### Fixed
- Fixed `Input chunk is not part of original chunk` exception when parsing empty key bodies

## [1.0.0-alpha3] - 2020-02-01
### Added
- `SeekableString` has been added to have a reusable component that seeks for tokens inside strings

### Changed
- Standard package output now contains meta-data for easier access of i18n package format version during runtime
- Key definitions in i18n output now contain their original definition value

### Fixed
- Correctly escape all strings written to source code instead of just passing them to `addslashes()`

## [1.0.0-alpha2] - 2020-01-21
### Fixed
- Fixed a bug where the `CurlyBracesKeyParser` would incorrectly return tokens for keys with more than two tokens
  
### Changed
- Improved `ParserException` messages

## [1.0.0-alpha1] - 2020-01-19
🥳 Initial Release