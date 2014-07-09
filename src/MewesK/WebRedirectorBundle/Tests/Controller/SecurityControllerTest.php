<?php

namespace MewesK\WebRedirectorBundle\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\BrowserKit\Client;

class SecurityControllerTest extends WebTestCase
{
    const USERNAME = 'admin';
    const PASSWORD = 'kitten';

    public function testLogin()
    {
        $client = static::createClient();
        $client->followRedirects();

        // Test login form

        $crawler = $client->request('GET', '/login');
        $this->assertTrue($crawler->filter('html:contains("Please sign in")')->count() > 0);

        // Login

        $crawler = self::login($client);

        // Test index

        $this->assertTrue(
            $crawler->filter('html:contains("Redirects")')->count() > 0 && $crawler->filter('html:contains("Create")')->count() > 0
        );

        // Test logout

        $crawler = $client->request('GET', '/logout');
        $this->assertTrue($crawler->filter('html:contains("Please sign in")')->count() > 0);
    }

    /**
     * @param Client $client
     *
     * @return \Symfony\Component\DomCrawler\Crawler
     */
    static function login(Client $client) {
        $crawler = $client->request('GET', '/login');
        $form = $crawler->selectButton('login')->form(array(
            '_username' => self::USERNAME,
            '_password' => self::PASSWORD
        ));
        return $client->submit($form);
    }
}
 