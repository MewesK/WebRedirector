<?php

namespace MewesK\WebRedirectorBundle\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class DefaultControllerTest extends WebTestCase
{
    public function testIndex()
    {
        $client = static::createClient();

        $crawler = $client->request('GET', '/foobar');
        $this->assertTrue($crawler->filter('html:contains("404 Unable to find a matching redirect.")')->count() > 0);
    }
}
