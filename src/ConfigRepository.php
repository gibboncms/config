<?php

namespace GibbonCms\Config;

use GibbonCms\Gibbon\Filesystems\Cache;
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
     * We're using a cache because file io is slow
     * 
     * @var \GibbonCms\Gibbon\Filesystems\Cache
     */
    protected $cache;

    /**
     * @param  \GibbonCms\Gibbon\Filesystems\Filesystem $filesystem
     * @param  \GibbonCms\Gibbon\Filesystems\Cache $cache
     */
    public function __construct(Filesystem $filesystem, Cache $cache)
    {
        $this->filesystem = $filesystem;

        $this->cache = $cache;
        $this->cache->rebuild();

        $this->yaml = new Yaml;
    }

    /**
     * Find an entity by key (supports dot notation and defaults)
     *
     * @param  string|null $key
     * @return mixed
     */
    public function find($key = null)
    {
        return Arr::get($this->cache->all(), $key);
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
     * This method does NOT persist to the filesystem and does NOT persist to the cache
     * 
     * @param  string $key
     * @param  mixed $value
     * @return array
     */
    public function set($key, $value)
    {
        return Arr::set($this->cache->all(), $key, $value);
    }

    /**
     * Parse the config files and save the values in the cache and in memory
     * 
     * @return void
     */
    public function build()
    {
        $files = $this->filesystem->listFiles();

        $this->cache->clear();

        foreach ($files as $file) {
            if ($file['extension'] == 'yml' || $file['extension'] == 'yaml') {
                $this->cache->put($file['filename'], $this->yaml->parse($this->filesystem->read($file['path'])));
            }
        }

        $this->cache->persist();
    }
}
