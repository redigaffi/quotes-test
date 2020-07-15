<?php


namespace App\Repository\Quotes;


use App\Dto\Quotes;
use League\Flysystem\Filesystem;
use Symfony\Component\String\Slugger\SluggerInterface;

class FileSystemQuotesRepository implements QuotesRepository
{
    private $sourceFilePath;
    const SOURCE = 'file_system';

    private $fileSystem;
    private $slugger;

    /**
     * FileSystemQuotesRepository constructor.
     * @param string $sourceFilePath
     * @param Filesystem $fileSystem
     * @param SluggerInterface $slugger
     */
    public function __construct(string $sourceFilePath, Filesystem $fileSystem, SluggerInterface $slugger)
    {
        $this->sourceFilePath = $sourceFilePath;
        $this->fileSystem = $fileSystem;
        $this->slugger = $slugger;
    }


    /**
     * @param string $name
     * @param int $limit
     * @return Quotes
     * @throws \Exception
     */
    public function get(string $name, int $limit): Quotes
    {
        if (!$this->fileSystem->has($this->sourceFilePath)) {
            throw new \Exception('Quotes source not found for file system adapter');
        }

        $quotes = []; $count = 0;
        $data = json_decode($this->fileSystem->read($this->sourceFilePath), true)['quotes'];

        foreach ($data as $quote) {
            if ($this->slugger->slug(mb_strtolower($quote['author']))->equalsTo($name) && $limit > 0) {
                $limit--;
                $quotes[] = $quote['quote'];
                $count++;
            }
        }

        return new Quotes($quotes, $name, $count, self::SOURCE);
    }

}