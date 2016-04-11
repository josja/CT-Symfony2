<?php

namespace AppBundle\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class DefaultControllerTest extends WebTestCase
{
    public function testIndex()
    {
        $client = static::createClient();

        $crawler = $client->request('GET', '/');

        $this->assertEquals(200, $client->getResponse()->getStatusCode(), "Unexpected HTTP status code for GET /");

        $this->assertContains('Todo', $client->getResponse()->getContent(), true);
        $this->assertContains('Priority', $client->getResponse()->getContent(), true);
        $this->assertContains('Created', $client->getResponse()->getContent(), true);
        $this->assertContains('Due', $client->getResponse()->getContent(), true);
        $this->assertContains('Actions', $client->getResponse()->getContent(), true);
        $this->assertContains('New Todo', $client->getResponse()->getContent(), true);
    }
}
