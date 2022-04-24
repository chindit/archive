# PHP Archive

A simple archive handler in PHP

## Installation

Simply add this package to your `composer.json`
```bash
composer require chindit/archive
```

## Usage

Usage is really simple.

Just call the  `extract` method of the `Archive` class, and that's it.

```php
$isExtractionSuccessful = Chindit\Archive::extract('/path/to/my/archive.zip', '/path/to/extract');
```

## Supported formats

At the moment, only ZIP, TAR, TAR.GZ, TAR.BZ2 and RAR are supported.

Please note that you need either `php-rar` extension or `unrar` binary available on your system in order to process `.rar` files.


