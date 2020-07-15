<?php


namespace App\Service\Quote;


use App\Response\QuoteResponse;

interface Quote
{
    public function getQuotes(string $name, int $limit): QuoteResponse;
}