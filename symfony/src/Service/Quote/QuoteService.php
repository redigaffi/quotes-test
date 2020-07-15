<?php


namespace App\Service\Quote;


use App\Exception\InvalidAuthorNameException;
use App\Exception\RequestedQuotesExceedsAllowedException;
use App\Repository\Quotes\QuotesRepository;
use App\Response\QuoteResponse;
use App\Service\Cache\Cache;


class QuoteService implements Quote
{

    private $quoteRepository;
    private $cache;
    private $slugger;
    private $stringManipulation;
    private $quoteLimit;

    /**
     * QuoteService constructor.
     * @param QuotesRepository $quoteRepository
     * @param Cache $cache
     * @param $stringManipulation
     * @param int $quoteLimit
     */
    public function __construct(QuotesRepository $quoteRepository, Cache $cache, $stringManipulation, int $quoteLimit)
    {
        $this->quoteRepository = $quoteRepository;
        $this->cache = $cache;
        $this->stringManipulation = $stringManipulation;
        $this->quoteLimit = $quoteLimit;
    }

    /**
     * @param string $name
     * @param int $limit
     * @return QuoteResponse
     */
    public function getQuotes(string $name, int $limit): QuoteResponse
    {
        $this->validateRequest($name, $limit);

        $quotes = json_decode($this->cache->get($this->cacheKey($name, $limit)), true);

        if (is_null($quotes)) {
            $freshQuotes = $this->quoteRepository->get($name, $limit);
            if ($freshQuotes->getCount() < 1) {
                return new QuoteResponse([]);
            }

            $quotes = $this->stringManipulation->shoutStringFromArray($freshQuotes->getQuotes());
            $this->cache->add($this->cacheKey($name, $limit), json_encode($quotes));
        }

        return new QuoteResponse($quotes);
    }

    /**
     * @param string $name
     * @param int $limit
     */
    private function validateRequest(string $name, int $limit): void
    {
        // Is name slug
        $slugPattern = preg_match('/^[a-z0-9]+-[a-z0-9]+$/', $name, $matches);
        if ($slugPattern === 0) {
            throw new InvalidAuthorNameException();
        }

        if ($limit > $this->quoteLimit) {
            throw new RequestedQuotesExceedsAllowedException();
        }
    }

    /**
     * @param string $name
     * @param int $limit
     * @return string
     */
    private function cacheKey(string $name, int $limit): string
    {
        return $name.$limit;
    }
}