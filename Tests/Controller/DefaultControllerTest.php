<?php

namespace Tests\ForumBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class DefaultControllerTest extends WebTestCase
{
    public function testIndex()
    {
        $client = static::createClient();

        $crawler = $client->request('GET', '/blog');

        $this->assertEquals(200, $client->getResponse()->getStatusCode());
    }
}
