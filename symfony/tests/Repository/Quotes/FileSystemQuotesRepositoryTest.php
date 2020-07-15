<?php


namespace App\Tests\Repository\Quotes;


use App\Dto\Quotes;
use App\Repository\Quotes\FileSystemQuotesRepository;
use League\Flysystem\Filesystem;
use PHPUnit\Framework\TestCase;
use Symfony\Component\String\Slugger\AsciiSlugger;

class FileSystemQuotesRepositoryTest extends TestCase
{

    private $fileSystemMock;
    const QUOTES_PATH = './static/quotes/quotes.json';

    protected function setUp()
    {
        $this->fileSystemMock = $this->createMock(Filesystem::class);
    }

    /**
     * @param $data
     * @param $author
     * @throws \Exception
     * @dataProvider quotesProvider
     */
    public function testThatCorrectQuotesAreReturned($data, $author) {
        $this->fileSystemMock
            ->expects($this->once())
            ->method('has')
            ->willReturn(true);

        $this->fileSystemMock
            ->expects($this->once())
            ->method('read')
            ->with('./static/quotes/quotes.json')
            ->willReturn(json_encode($data));

        $fileSystemQuotesRepository = new FileSystemQuotesRepository(self::QUOTES_PATH, $this->fileSystemMock,
            new AsciiSlugger());
        $quotes = $fileSystemQuotesRepository->get($author, 2);


        $this->assertInstanceOf(Quotes::class, $quotes);
        $this->assertCount(count($data['quotes']), $quotes->getQuotes());
    }

    public function testThatExceptionIsThrownIfPathIncorrect() {
        $this->fileSystemMock
            ->expects($this->once())
            ->method('has')
            ->willReturn(false);

        $this->expectExceptionMessage('Quotes source not found for file system adapter');
        $fileSystemQuotesRepository = new FileSystemQuotesRepository('1.txt', $this->fileSystemMock,
            new AsciiSlugger());

        $fileSystemQuotesRepository->get("", 0);
    }

    public function quotesProvider() {
        return [
            [
                // Test case 1
                [
                    'quotes' => [
                        ['author' => 'Zig Ziglar', 'quote' => 'test1'],
                        ['author' => 'Zig Ziglar', 'quote' => 'test2']
                    ],
                ],
                "zig-ziglar"
                // End Test case 1
            ],
            [
                // Test case 2
                [
                    'quotes' => [
                        ['author' => 'Robert Frost', 'quote' => 'test3']
                    ]
                ],
                "robert-frost"
                // End Test case 2
            ]
        ];
    }


}