<?php

namespace GibbonCms\Config\Tests;

use GibbonCms\Config\Config;

class ConfigTest extends TestCase
{
    function setUp()
    {
        $this->config = new Config($this->fixtures . '/settings');
    }

    /** @test */
    function it_is_initializable()
    {
        $this->assertInstanceOf(Config::class, $this->config);
    }
}
