<?php

namespace Chindit\Archive\Handler;

interface ArchiveHandlerInterface
{
    public static function extensions(): array;
    public static function mimes(): array;
    public static function isEnabled(): bool;
    public static function supports(string $mime): bool;
    public function extract(string $outputDirectory);
}