<?php

namespace Chindit\Archive\Tests\Handler;

use Chindit\Archive\Exception\UnreadableArchiveException;
use Chindit\Archive\Exception\UnsupportedArchiveType;
use Chindit\Archive\Exception\UnwritableOutputDirectory;
use Chindit\Archive\Handler\ZipTarHandler;
use PHPUnit\Framework\TestCase;

class ZipTarHandlerTest extends TestCase
{
    public function testSupportedExtensions(): void
    {
        $this->assertEquals([
            '.zip',
            '.tar',
            '.tar.gz',
            '.tar.bz2',
        ], ZipTarHandler::extensions());
    }

    public function testSupportedMimeTypes(): void
    {
        $this->assertEquals([
            'application/x-tar',
            'application/x-bzip2',
            'application/x-gzip',
            'application/gzip',
            'application/x-gtar',
            'application/zip',
        ], ZipTarHandler::mimes());
    }

    public function testIsEnabled(): void
    {
        $this->assertTrue(ZipTarHandler::isEnabled());
    }

    public function testUnreadableFileThrowsException(): void
    {
        $this->expectExceptionObject(new UnreadableArchiveException('File /fakeFile.zip cannot be read'));

        new ZipTarHandler('/fakeFile.zip');
    }

    public function testWrongFileFormatException(): void
    {
        $this->expectExceptionObject(new UnsupportedArchiveType('File of type image/jpeg is not supported by the Chindit\Archive\Handler\ZipTarHandler handler'));

        $handler = new ZipTarHandler(__DIR__ . '/../testFiles/flower.jpg');
        $handler->extract(sys_get_temp_dir());
    }

    /**
     * Due to directory encapsulation and root access, we cannot access an unreadable directory in GitHub o_O
     */
    /*public function testWrongExtractionPath(): void
    {
        $this->expectExceptionObject(new UnwritableOutputDirectory());

        $handler = new ZipTarHandler(__DIR__ . '/../testFiles/test.zip');

        $handler->extract(__DIR__ . '/../../');
    }*/

    public function testZipFileExtraction(): void
    {
        $file = __DIR__ . '/../testFiles/test.zip';

        $handler = new ZipTarHandler($file);

        $this->assertTrue($handler->extract(sys_get_temp_dir()));

        $this->assertFileExists(sys_get_temp_dir() . '/688-200x300.jpg');
        $this->assertFileExists(sys_get_temp_dir() . '/882-300x200.jpg');
    }

    public function testZipWithSubdirectories(): void
    {
        $handler = new ZipTarHandler(__DIR__ . '/../testFiles/subdir.zip');

        $this->assertTrue($handler->extract(sys_get_temp_dir() . '/temp/'));

        $this->assertFileExists(sys_get_temp_dir() . '/temp/237-536x354.jpg');
        $this->assertDirectoryExists(sys_get_temp_dir() . '/temp/self');
        $this->assertFileExists(sys_get_temp_dir() . '/temp/self/180_bw_a2pix.jpg');
    }

    public function testTarGzFileExtraction(): void
    {
        $file = __DIR__ . '/../testFiles/test.tar.gz';

        $handler = new ZipTarHandler($file);

        $this->assertTrue($handler->extract(sys_get_temp_dir()));

        $this->assertFileExists(sys_get_temp_dir() . '/5de417b3b349f899c6fdfd2dbda8dd15.jpg');
        $this->assertFileExists(sys_get_temp_dir() . '/790a6bcd44eaef961f35df325fab3bf0.jpg');
    }
}
