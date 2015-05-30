<?php

namespace GibbonCms\Config;

use GibbonCms\Gibbon\Filesystems\FileCache;
use GibbonCms\Gibbon\Filesystems\Filesystem;
use GibbonCms\Gibbon\Modules\Module;

class Config implements Module
{
    /**
     * @var \GibbonCms\Config\ConfigRepository
     */
    protected $repository;

    /**
     * @param  \GibbonCms\Gibbon\Filesystems\Filesystem $filesystem
     * @param  string $directory
     * @param  \GibbonCms\Gibbon\Filesystems\FileCache $fileCache
     */
    public function __construct(Filesystem $filesystem, $directory, FileCache $fileCache)
    {
        $this->repository = new ConfigRepository($filesystem, $directory, $fileCache);
    }

    /**
     * @param string $id
     * @return \GibbonCms\Pages\Page
     */
    public function get($id)
    {
        return $this->repository->find($id);
    }

    /**
     * @return void
     */
    public function setUp()
    {
        $this->repository->build();
    }
}
