<?php

namespace Chindit\Archive\Handler;

use Chindit\Archive\Exception\UnreadableArchiveException;
use Chindit\Archive\Exception\UnwritableOutputDirectory;

abstract class AbstractHandler
{
    public function __construct(protected $file)
    {
        if (!is_readable($this->file)) {
            throw new UnreadableArchiveException(sprintf("File %s cannot be read", $this->file));
        }
    }

    public function extract(string $outputDirectory)
    {
        if (!is_writable($outputDirectory)) {
            throw new UnwritableOutputDirectory();
        }
    }

    public static function supports(string $mime): bool
    {
        return static::isEnabled() && in_array($mime, static::mimes());
    }
}