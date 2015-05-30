<?php

namespace GibbonCms\Config\Test;

use GibbonCms\Config\Config;
use GibbonCms\Gibbon\Filesystems\FileCache;
use GibbonCms\Gibbon\Filesystems\PlainFilesystem;

class ConfigTest extends TestCase
{
    function setUp()
    {
        $this->config = new Config(
            new PlainFilesystem($this->fixtures),
            'settings',
            new FileCache($this->fixtures.'/settings/.cache')
        );

        $this->config->setUp();
    }

    /** @test */
    function it_is_initializable()
    {
        $this->assertInstanceOf(Config::class, $this->config);
    }
}
