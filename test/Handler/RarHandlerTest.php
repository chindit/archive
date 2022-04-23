<?php

namespace Chindit\Archive\Tests\Handler;

use Chindit\Archive\Handler\RarHandler;
use PHPUnit\Framework\TestCase;

class RarHandlerTest extends TestCase
{
    public function testSupportedExtensions(): void
    {
        $this->assertEquals([
            '.rar',
        ], RarHandler::extensions());
    }

    public function testSupportedMimeTypes(): void
    {
        $this->assertEquals([
            'application/x-rar',
            'application/x-rar-compressed',
        ], RarHandler::mimes());
    }

    public function testIsEnabled(): void
    {
        $this->assertTrue(RarHandler::isEnabled());
    }

    public function testExtract(): void
    {
        $file = __DIR__ . '/../testFiles/test.rar';

        $handler = new RarHandler($file);

        $this->assertTrue($handler->extract(sys_get_temp_dir() . '/rar/'));

        $this->assertFileExists(sys_get_temp_dir() . '/rar/688-200x300.jpg');
        $this->assertFileExists(sys_get_temp_dir() . '/rar/882-300x200.jpg');
    }
}
