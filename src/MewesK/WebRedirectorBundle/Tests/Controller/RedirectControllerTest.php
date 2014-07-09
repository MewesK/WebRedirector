<?php

namespace MewesK\WebRedirectorBundle\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class RedirectControllerTest extends WebTestCase
{
    public function testIndex()
    {
        $client = static::createClient();
        $client->followRedirects();

        // Test access control

        $crawler = $client->request('GET', '/admin');
        $this->assertTrue($crawler->filter('html:contains("Please sign in")')->count() > 0);

        // Login

        $crawler = SecurityControllerTest::login($client);

        // Test index

        $this->assertTrue(
            $crawler->filter('html:contains("Redirects")')->count() > 0 && $crawler->filter('html:contains("Create")')->count() > 0
        );

        // Test new redirect

        $link = $crawler->filter('a:contains("Create")')->link();
        $crawler = $client->request($link->getMethod(), $link->getUri());
        $this->assertTrue($crawler->filter('html:contains("New redirect")')->count() > 0);
    }
}
