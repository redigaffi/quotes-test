<?php


namespace App\Dto;


class Quotes
{
    private $quotes = [];
    private $name;
    private $count;
    private $source;

    /**
     * Quotes constructor.
     * @param array $quotes
     * @param string $name
     * @param int $count
     * @param string $source
     */
    public function __construct(array $quotes, string $name, int $count, string $source)
    {
        $this->quotes = $quotes;
        $this->name = $name;
        $this->count = $count;
        $this->source = $source;
    }

    /**
     * @return array
     */
    public function getQuotes(): array
    {
        return $this->quotes;
    }

    /**
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * @return int
     */
    public function getCount(): int
    {
        return $this->count;
    }

    /**
     * @return string
     */
    public function getSource(): string
    {
        return $this->source;
    }

    public function __toString()
    {
        return json_encode($this->quotes);
    }


}