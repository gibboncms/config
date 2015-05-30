<?php

namespace GibbonCms\Config\Test;

use GibbonCms\Config\ConfigRepository;
use GibbonCms\Gibbon\Filesystems\PlainFilesystem;
use GibbonCms\Gibbon\Filesystems\FileCache;

class ConfigRepositoryTest extends TestCase
{
    function setUp()
    {
        $this->configRepository = new ConfigRepository(
            new PlainFilesystem($this->fixtures),
            'settings',
            new FileCache($this->fixtures . '/settings/.cache')
        );

        $this->configRepository->build();
    }

    /** @test */
    function it_is_initializable()
    {
        $this->assertInstanceOf(ConfigRepository::class, $this->configRepository);
    }

    /** @test */
    function it_gets_a_value_via_dot_notation()
    {
        $this->assertEquals('GibbonCms', $this->configRepository->find('site.name'));
        $this->assertEquals('gibboncms.io', $this->configRepository->find('site.url'));
        $this->assertEquals('file', $this->configRepository->find('database.driver'));
    }

    /** @test */
    function it_gets_all_values()
    {
        $expected = [
            'site' => [ 'name' => 'GibbonCms', 'url' => 'gibboncms.io' ],
            'database' => [ 'driver' => 'file' ],
        ];

        $this->assertEquals($expected, $this->configRepository->getAll());
    }
}
