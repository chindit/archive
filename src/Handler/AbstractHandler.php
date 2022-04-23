<?php

namespace Chindit\Archive\Handler;

use Chindit\Archive\Exception\UnreadableArchiveException;
use Chindit\Archive\Exception\UnsupportedArchiveType;
use Chindit\Archive\Exception\UnwritableOutputDirectory;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\Mime\MimeTypes;

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
        if ((!is_dir($outputDirectory) && !$this->attemptDirectoryCreation($outputDirectory)) || !is_writable($outputDirectory)) {
            throw new UnwritableOutputDirectory();
        }

        $fileMime = (new MimeTypes())->guessMimeType($this->file);
        if (!in_array($fileMime, static::mimes(), true)) {
            throw new UnsupportedArchiveType(sprintf("File of type %s is not supported by the %s handler", $fileMime, static::class));
        }
    }

    public static function supports(string $mime): bool
    {
        return static::isEnabled() && in_array($mime, static::mimes());
    }

    protected function attemptDirectoryCreation(string $outputDirectory): bool
    {
        $fileSystem = new Filesystem();
        try {
            $fileSystem->mkdir($outputDirectory);
        } catch (\Throwable $t) {
            return false;
        }

        return true;
    }
}