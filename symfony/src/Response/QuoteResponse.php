<?php


namespace App\Response;

class QuoteResponse
{
    private $quotes = [];

    /**
     * QuoteResponse constructor.
     * @param array $quotes
     */
    public function __construct(array $quotes)
    {
        $this->quotes = $quotes;
    }

    /**
     * @return array
     */
    public function getQuotes(): array
    {
        return $this->quotes;
    }


    public function __toString()
    {
        return json_encode($this->quotes);
    }
}