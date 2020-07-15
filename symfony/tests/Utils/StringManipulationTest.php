<?php


namespace App\Tests\Utils;


use App\Utils\StringManipulation;
use PHPUnit\Framework\TestCase;

class StringManipulationTest extends TestCase
{

    private $stringManipulation;

    protected function setUp()
    {
        $this->stringManipulation = new StringManipulation();
    }


    /**
     * @dataProvider dataProvider
     * @param $string
     * @param $expected
     */
    public function testStringRemoveDotsAndExclamationAndConvertToShouting(string $string, string $expected)
    {
        $result = $this->stringManipulation->shoutString($string);
        $this->assertEquals($expected, $result);
    }

    public function dataProvider() {
        return [
            [
                "a..!",
                "A!"
            ],
            [
                "B!!!",
                "B!",
            ],
            [
                "¡b!",
                "¡B!",
            ],
            [
                "AsDaD .. !",
                "ASDAD!",
            ],
        ];
    }

}