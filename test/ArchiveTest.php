<?php

namespace Chindit\Archive\Tests;

use Chindit\Archive\Archive;
use PHPUnit\Framework\TestCase;

class ArchiveTest extends TestCase
{

    public function testIsSupportedArchiveWithWrongFormat(): void
    {
        $this->assertFalse(Archive::isSupportedArchive(__DIR__ . '/testFiles/flower.jpg'));
    }

    public function testIsSupportedArchive(): void
    {
        $this->assertTrue(Archive::isSupportedArchive(__DIR__ . '/testFiles/subdir.zip'));
        $this->assertTrue(Archive::isSupportedArchive(__DIR__ . '/testFiles/test.tar.gz'));
        $this->assertTrue(Archive::isSupportedArchive(__DIR__ . '/testFiles/test.rar'));
    }

    public function testExtract()
    {
        $this->assertTrue(Archive::extract(__DIR__ . '/testFiles/test.zip', sys_get_temp_dir() . '/archive/'));

        $this->assertFileExists(sys_get_temp_dir() . '/archive/688-200x300.jpg');
        $this->assertFileExists(sys_get_temp_dir() . '/archive/882-300x200.jpg');
    }
}
