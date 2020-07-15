<?php


namespace App\Service\Cache;



interface Cache
{
    /**
     * Stores in cache given key and content
     *
     * @param string $key
     * @param string $value
     */
    public function add(string $key, string $value): void;

    /**
     * Retrieves content from cache given a key
     * @param string $key
     * @return string
     */
    public function get(string $key): string;
}