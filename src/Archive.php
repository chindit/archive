<?php
declare(strict_types=1);

namespace Chindit\Archive;

use Chindit\Archive\Exception\UnsupportedArchiveType;
use Chindit\Archive\Handler\ArchiveHandlerInterface;
use Chindit\Archive\Handler\RarHandler;
use Chindit\Archive\Handler\ZipTarHandler;
use Symfony\Component\HttpFoundation\File\File;

final class Archive
{
    /** @var array|string[]  */
    private array $handlers = [
        RarHandler::class,
        ZipTarHandler::class,
    ];

    public static function isSupportedArchive(string $sourceFile): bool
    {
        $archive = new self();
        try {
            $archive->findSupportedExtractor($sourceFile);

            return true;
        } catch (\Throwable $t) {
            return false;
        }
    }

    public static function extract(string $sourceFile, string $targetDirectory): bool
    {
        $archive = new self();
        $extractorClass = $archive->findSupportedExtractor($sourceFile);
        $extractor = new $extractorClass($sourceFile);

        return $extractor->extract($targetDirectory);
    }

    public function findSupportedExtractor(string $sourceFile): ArchiveHandlerInterface
    {
        $mime = (new File($sourceFile, false))->getMimeType();

        foreach ($this->handlers as $handler) {
            if ($handler::supports($mime)) {
                return $handler;
            }
        }

        throw new UnsupportedArchiveType(sprintf('Archive format %s is not supported yet.', $mime));
    }
}