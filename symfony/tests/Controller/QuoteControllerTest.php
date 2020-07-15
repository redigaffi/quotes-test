<?php


namespace App\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class QuoteControllerTest extends WebTestCase
{
    public function testGetQuotes(): void
    {
        $client = static::createClient();
        $client->request('GET', '/shout/steve-jobs/2');
        $this->assertEquals(200, $client->getResponse()->getStatusCode());
    }

    public function testGetQuotesWithWrongAuthorNameFormat()
    {
        $client = static::createClient();
        $client->request('GET', '/shout/stevejobs/2');
        $this->assertEquals(400, $client->getResponse()->getStatusCode());
        $this->assertEquals("Provider author name is not following the slug format.",
            json_decode($client->getResponse()->getContent(), true)['exception']['message']);

    }

    public function testGetQuotesWithQuoteLimitReached()
    {
        $client = static::createClient();
        $client->request('GET', '/shout/steve-jobs/50000');
        $this->assertEquals(400, $client->getResponse()->getStatusCode());
        $this->assertEquals("Amount of quotes requested exceeds the limit.",
            json_decode($client->getResponse()->getContent(), true)['exception']['message']);

    }
}