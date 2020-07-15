<?php


namespace App\Tests\Service\Cache;


use App\Service\Cache\RedisCacheAdapter;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Cache\Adapter\AdapterInterface;
use Symfony\Contracts\Cache\ItemInterface;

class RedisCacheAdapterTest extends TestCase
{
    private $redisClientMock;
    private $ttl;

    protected function setUp()
    {
        $this->redisClientMock = $this->createMock(AdapterInterface::class);
        $this->ttl = 10;
    }

    /**
     * @throws \Psr\Cache\InvalidArgumentException
     */
    public function testStoringValuesInCache()
    {
        $cacheItemMock = $this->createMock(ItemInterface::class);

        $this->redisClientMock
            ->expects($this->once())
            ->method('getItem')
            ->willReturn($cacheItemMock);

        $cacheItemMock
            ->expects($this->once())
            ->method('set');

        $cacheItemMock
            ->expects($this->once())
            ->method('expiresAfter');

        $this->redisClientMock
            ->expects($this->once())
            ->method('save')
            ->with($cacheItemMock);

        $redisCacheAdapter = new RedisCacheAdapter($this->redisClientMock, $this->ttl);
        $redisCacheAdapter->add('test', 'test');
    }

    public function testGettingExistingItemFromCache()
    {
        $cacheItemMock = $this->createMock(ItemInterface::class);

        $this->redisClientMock
            ->expects($this->once())
            ->method('getItem')
            ->with('key')
            ->willReturn($cacheItemMock);

        $cacheItemMock
            ->expects($this->exactly(1))
            ->method('get')
            ->willReturn('test');

        $redisCacheAdapter = new RedisCacheAdapter($this->redisClientMock, $this->ttl);
        $result = $redisCacheAdapter->get('key');
        $this->assertEquals('test', $result);

    }

    public function testGettingNonExistingItemFromCacheShouldReturnEmptyString()
    {
        $cacheItemMock = $this->createMock(ItemInterface::class);

        $this->redisClientMock
            ->expects($this->once())
            ->method('getItem')
            ->with('key')
            ->willReturn($cacheItemMock);

        $cacheItemMock
            ->expects($this->exactly(1))
            ->method('get')
            ->willReturn(null);

        $redisCacheAdapter = new RedisCacheAdapter($this->redisClientMock, $this->ttl);
        $result = $redisCacheAdapter->get('key');
        $this->assertEquals('', $result);

    }


}