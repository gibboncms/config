<?php

namespace GibbonCms\Config;

use GibbonCms\Gibbon\Filesystems\Filesystem;
use GibbonCms\Gibbon\Repositories\Repository;
use Illuminate\Support\Arr;
use Symfony\Component\Yaml\Yaml;

class ConfigRepository implements Repository
{
    /**
     * The filesystem is used to read and write files
     * 
     * @var \GibbonCms\Gibbon\Filesystems\Filesystem
     */
    protected $filesystem;

    /**
     * In memory cache containing the values
     */
    protected $values;

    /**
     * @param \GibbonCms\Gibbon\Interfaces\Filesystem $filesystem
     */
    public function __construct(Filesystem $filesystem)
    {
        $this->filesystem = $filesystem;
        $this->yaml = new Yaml;

        $this->build();
    }

    /**
     * Find an entity by key (supports dot notation and defaults)
     *
     * @param string|null $key
     * @return mixed
     */
    public function find($key = null)
    {
        return Arr::get($this->values, $key);
    }

    /**
     * Return all entities
     * 
     * @return mixed[]
     */
    public function getAll()
    {
        return $this->find();
    }

    /**
     * Set an array item to a given value using "dot" notation.
     * If no key is given to the method, the entire array will be replaced.
     * 
     * Source: https://github.com/illuminate/support/blob/master/Arr.php
     *
     * @param string $key
     * @param mixed $value
     * @return array
     */
    public function set($key, $value)
    {
        return Arr::set($this->values, $key, $value);
    }

    /**
     * Parse the config files and save the values in memory
     * 
     * @return void
     */
    protected function build()
    {
        $files = $this->filesystem->listFiles();

        $values = [];

        foreach ($files as $file) {
            if ($file['extension'] == 'yml' || $file['extension'] == 'yaml') {
                $values[$file['filename']] = $this->yaml->parse($this->filesystem->read($file['path']));
            }
        }

        $this->values = $values;
    }
}
