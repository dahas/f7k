<?php declare(strict_types=1);

namespace f7k\Sources;

use f7k\Sources\interfaces\CacheInterface;

class Cache implements CacheInterface {

    private string $cacheDir = ROOT . "/.cache";

    public function get($key, $default = null): mixed
    {
        $cache = file($this->cacheDir . "/{$key}.cache")[0];
        return unserialize($cache);
    }

    public function set($key, $value, $ttl = null)
    {
        $handle = fopen($this->cacheDir . "/{$key}.cache", "w");
        if ($handle && fwrite($handle, serialize($value))) {
            return true;
        }
        return false;
    }

    public function delete($key)
    {
        return unlink($this->cacheDir . "/{$key}.cache");
    }

    public function clear()
    {
        $res = array_map('unlink', array_filter((array) glob("{$this->cacheDir}/*")));
        if (!empty($res)) {
            return true;
        }
        return false;
    }

    public function getMultiple(iterable $keys, $default = null): iterable
    {
        $res = [];
        foreach ($keys as $key) {
            $res[$key] = $this->get($key);
        }
        return $res;
    }

    public function setMultiple(iterable $values, $ttl = null): bool
    {
        $res = false;
        foreach ($values as $key => $val) {
            $res = $this->set($key, $val);
        }
        return $res;
    }

    public function deleteMultiple($keys)
    {
        $res = false;
        foreach ($keys as $key) {
            $res = $this->delete($key);
        }
        return $res;
    }

    public function has($key)
    {
        return file_exists($this->cacheDir . "/{$key}.cache");
    }
}