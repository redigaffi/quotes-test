<?php


namespace App\Utils;


use function Symfony\Component\String\u;

class StringManipulation
{
    /**
     * Converts given string to uppercase and appends exclamation mark.
     * @param string $input
     * @return string
     */
    public function shoutString(string $input): string
    {
        return u($input)
                    ->trimEnd('.! ')
                    ->upper()
                    ->append("!")
                    ->toString();
    }

    /**
     * @param array $input
     * @return array
     */
    public function shoutStringFromArray(array $input): array
    {
        $result = [];
        foreach ($input as $text) $result[] = $this->shoutString($text);
        return $result;
    }
}