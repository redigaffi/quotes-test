<?php


namespace App\Tests\Service\Quote;


use App\Dto\Quotes;
use App\Exception\InvalidAuthorNameException;
use App\Exception\RequestedQuotesExceedsAllowedException;
use App\Repository\Quotes\QuotesRepository;
use App\Response\QuoteResponse;
use App\Service\Cache\Cache;
use App\Service\Quote\QuoteService;
use App\Utils\StringManipulation;
use PHPUnit\Framework\TestCase;

class QuoteServiceTest extends TestCase
{
    private $quoteRepositoryMock;
    private $cacheMock;
    private $stringManipulationMock;
    private $quoteService;
    private $quoteLimit = 10;

    protected function setUp()
    {
        $this->quoteRepositoryMock = $this->createMock(QuotesRepository::class);
        $this->cacheMock = $this->createMock(Cache::class);
        $this->stringManipulationMock = $this->createMock(StringManipulation::class);
        $this->quoteService = new QuoteService($this->quoteRepositoryMock,$this->cacheMock, $this->stringManipulationMock, $this->quoteLimit);
    }

    public function testNoQuotesInCacheNorRepositoryShouldReturnEmptyQuoteResponse()
    {
        $name = 'test-test';
        $limit = 1;

        $this->cacheMock
            ->expects($this->once())
            ->method('get')
            ->with($name.$limit)
            ->willReturn('');

        $this->quoteRepositoryMock
            ->expects($this->once())
            ->method('get')
            ->with($name, $limit)
            ->willReturn(new Quotes([], '', 0, ''));

        $result = $this->quoteService->getQuotes($name, $limit);
        $this->assertInstanceOf(QuoteResponse::class, $result);
        $this->assertCount(0, $result->getQuotes());
    }

    public function testFoundItemInCacheShouldBeReturned()
    {
        $name = 'test-test';
        $limit = 1;

        $this->cacheMock
            ->expects($this->once())
            ->method('get')
            ->with($name.$limit)
            ->willReturn('["TEST!", "TEST1!"]');

        $this->quoteRepositoryMock
            ->expects($this->never())
            ->method('get');

        $this->stringManipulationMock
            ->expects($this->never())
            ->method('shoutStringFromArray');

        $this->cacheMock
            ->expects($this->never())
            ->method('add');

        $result = $this->quoteService->getQuotes($name, $limit);

        $this->assertInstanceOf(QuoteResponse::class, $result);
        $this->assertCount(2, $result->getQuotes());
        $this->assertEquals("TEST!", $result->getQuotes()[0]);
        $this->assertEquals("TEST1!", $result->getQuotes()[1]);
    }

    public function testItemNotFoundInCacheSearchInRepository()
    {
        $name = 'test-test';
        $limit = 1;
        $quotes = ["test", "test1"];
        $shouted = ["TEST!", "TEST1!"];

        $this->cacheMock
            ->expects($this->once())
            ->method('get')
            ->with($name.$limit)
            ->willReturn(''); // empty json

        $this->quoteRepositoryMock
            ->expects($this->once())
            ->method('get')
            ->with($name, $limit)
            ->willReturn(new Quotes($quotes, "test", 2, ''));

        $this->stringManipulationMock
            ->expects($this->once())
            ->method('shoutStringFromArray')
            ->with($quotes)
            ->willReturn($shouted);

        $this->cacheMock
            ->expects($this->once())
            ->method('add')
            ->with($name.$limit, json_encode($shouted));

        $result = $this->quoteService->getQuotes($name, $limit);
        $this->assertInstanceOf(QuoteResponse::class, $result);
        $this->assertCount(2, $result->getQuotes());
        $this->assertEquals("TEST!", $result->getQuotes()[0]);
        $this->assertEquals("TEST1!", $result->getQuotes()[1]);
    }

    public function testQuoteLimitExceedsMaximumQuotesThrowsException()
    {
        $this->expectException(RequestedQuotesExceedsAllowedException::class);

        $name = 'test-test';
        $limit = $this->quoteLimit + 1;

        $this->cacheMock
            ->expects($this->never())
            ->method('get');

        $this->quoteService->getQuotes($name, $limit);
    }

    public function testAuthorNameIsNotSluggedThrowsException()
    {
        $this->expectException(InvalidAuthorNameException::class);

        $name = 'test';
        $limit = $this->quoteLimit;

        $this->cacheMock
            ->expects($this->never())
            ->method('get');

        $this->quoteService->getQuotes($name, $limit);
    }
}