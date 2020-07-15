<?php


namespace App\Repository\Quotes;


use App\Dto\Quotes;

interface QuotesRepository
{
    public function get(string $name, int $limit): Quotes;
}