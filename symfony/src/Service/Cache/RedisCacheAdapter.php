<?php


namespace App\Service\Cache;


use DateInterval;
use Symfony\Component\Cache\Adapter\AdapterInterface;

class RedisCacheAdapter implements Cache
{
    private $redisClient;
    private $ttl;

    /**
     * RedisCacheAdapter constructor.
     * @param $redisClient
     */
    public function __construct(AdapterInterface $redisClient, int $ttl)
    {
        $this->redisClient = $redisClient;
        $this->ttl = $ttl;
    }

    /**
     * @param string $key
     * @param string $value
     * @throws \Psr\Cache\InvalidArgumentException
     */
    public function add(string $key, string $value): void
    {
        $item = $this->redisClient->getItem($key);
        $item->set($value);
        $item->expiresAfter(new DateInterval("PT{$this->ttl}M"));
        $this->redisClient->save($item);
    }

    /**
     * @param string $key
     * @return string
     */
    public function get(string $key): string
    {
        $result = $this->redisClient
            ->getItem($key)
            ->get();

        return $result === null ? '' : $result;
    }
}