<?php

namespace Chindit\Archive\Handler;

class ZipTarHandler extends AbstractHandler implements ArchiveHandlerInterface
{

    public static function extensions(): array
    {
        return [
            '.zip',
            '.tar',
            '.tar.gz',
            '.tar.bz2',
        ];
    }

    public static function mimes(): array
    {
        return [
            'application/x-tar',
            'application/x-bzip2',
            'application/x-gzip',
            'application/x-gtar',
            'application/zip',
        ];
    }

    public static function isEnabled(): bool
    {
        return class_exists(\PharData::class);
    }

    public function extract(string $outputDirectory): bool
    {
        $archive = new \PharData($this->file);

        return $archive->extractTo($outputDirectory, overwrite: true);
    }
}