<?php

namespace GibbonCms\Config;

use GibbonCms\Gibbon\Filesystems\Cache;
use GibbonCms\Gibbon\Filesystems\Filesystem;
use GibbonCms\Gibbon\Repositories\Repository;
use Illuminate\Support\Arr;
use Symfony\Component\Yaml\Parser as Yaml;

class ConfigRepository implements Repository
{
    /**
     * The filesystem is used to read and write files
     * 
     * @var \GibbonCms\Gibbon\Filesystems\Filesystem
     */
    protected $filesystem;

    /**
     * The directory in the filesystem where the config files are located
     * 
     * @var string
     */
    protected $directory;

    /**
     * We're using a cache because file io is slow
     * 
     * @var \GibbonCms\Gibbon\Filesystems\Cache
     */
    protected $cache;

    /**
     * In memory version of all the values
     * 
     * @var $array
     */
    protected $values;

    /**
     * The yaml parser
     * 
     * @var \Symfony\Component\Yaml\Parser
     */
    protected $yaml;

    /**
     * @param  \GibbonCms\Gibbon\Filesystems\Filesystem $filesystem
     * @param  string $directory
     * @param  \GibbonCms\Gibbon\Filesystems\Cache $cache
     */
    public function __construct(Filesystem $filesystem, $directory, Cache $cache)
    {
        $this->filesystem = $filesystem;
        $this->directory = $directory;

        $this->cache = $cache;
        $this->cache->rebuild();

        $this->updateValues();

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
     * This method does NOT persist to the filesystem and does NOT persist to the cache
     * 
     * @param  string $key
     * @param  mixed $value
     * @return array
     */
    public function set($key, $value)
    {
        return Arr::set($this->values, $key, $value);
    }

    /**
     * Parse the config files and save the values in the cache and in memory
     * 
     * @return void
     */
    public function build()
    {
        $files = $this->filesystem->listFiles($this->directory);

        $this->cache->clear();

        foreach ($files as $file) {
            if ($file['extension'] == 'yml' || $file['extension'] == 'yaml') {
                $this->cache->put($file['filename'], $this->yaml->parse($this->filesystem->read($file['path'])));
            }
        }

        $this->cache->persist();
        $this->updateValues();
    }

    /**
     * Update the in memory values
     * 
     * @return void
     */
    protected function updateValues()
    {
        $this->values = $this->cache->all();
    }
}
