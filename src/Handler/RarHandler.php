<?php

namespace Chindit\Archive\Handler;

use Chindit\Archive\Exception\UnsupportedMethodException;
use Symfony\Component\Process\Process;

class RarHandler extends AbstractHandler implements ArchiveHandlerInterface
{

    public static function extensions(): array
    {
        return [
            '.rar',
        ];
    }

    public static function mimes(): array
    {
        return [
            'application/x-rar',
            'application/x-rar-compressed',
        ];
    }

    public static function isEnabled(): bool
    {
        if(!class_exists(\RarArchive::class)) {
            $process = Process::fromShellCommandline('/usr/bin/unrar');
            $process->run();

            return $process->isSuccessful();
        }

        return true;
    }

    /**
     * Return the list of files content in the archive
     * @return array<int, string>
     */
    public function getContent(): array
    {
        if (!class_exists(\RarArchive::class)) {
            $process = Process::fromShellCommandline('/usr/bin/unrar l ' . $this->file);
            $process->run();

            return [$process->getOutput()];
        }

        $rar = RarArchive::open($this->file);
        if ($rar === false) {
            return [];
        }
        $files = $rar->getEntries();

        $fileList = [];

        if ($files === false) {
            return [];
        }

        foreach ($files as $entry) {
            $fileList[] = $entry->getName();
        }

        $rar->close();

        return $fileList;
    }

    /**
     * Extract archive in given path
     * @param string $outputDirectory
     * @return bool
     */
    public function extract(string $outputDirectory): bool
    {
        parent::extract($outputDirectory);

        if (class_exists(\RarArchive::class)) {
            $rar = RarArchive::open($this->file);
            if ($rar === false) {
                return false;
            }

            $entries = $rar->getEntries();

            if ($entries === false) {
                return false;
            }

            foreach ($entries as $entry) {
                $entry->extract($outputDirectory);
            }

            $rar->close();

            return true;
        } else {
            $process = Process::fromShellCommandline('/usr/bin/unrar x ' . $this->file . ' ' . $outputDirectory);
            $process->run();

            return $process->isSuccessful();
        }
    }

}