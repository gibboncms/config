<?php

namespace GibbonCms\Config;

use GibbonCms\Gibbon\Filesystems\PlainFilesystem;

class Config
{
    /**
     * @var \GibbonCms\Config\ConfigRepository
     */
    protected $repository;

    /**
     * @param string $directory
     */
    public function __construct($directory)
    {
        $this->repository = new ConfigRepository(
            new PlainFilesystem($directory)
        );
    }

    /**
     * @param string $id
     * @return \GibbonCms\Pages\Page
     */
    public function get($id)
    {
        return $this->repository->find($id);
    }
}
