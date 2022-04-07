<?php

namespace Chindit\Archive\Handler;

interface ArchiveHandlerInterface
{
    public static function extensions(): array;
    public static function mimes(): array;
    public static function isEnabled(): bool;
    public function extract(string $outputDirectory);
}