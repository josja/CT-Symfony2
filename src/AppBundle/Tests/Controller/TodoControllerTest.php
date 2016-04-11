<?php

namespace AppBundle\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class TodoControllerTest extends WebTestCase
{
    public function testCompleteUseCase()
    {
        // Create a new client to browse the application
        $client = static::createClient();

        // Create a new entry in the database
        $crawler = $client->request('GET', '/');

        // Check general content
        $this->assertEquals(200, $client->getResponse()->getStatusCode(), "Unexpected HTTP status code for GET /");

        $content = $client->getResponse()->getContent();
        $this->assertContains('Todo', $content, true);
        $this->assertContains('Priority', $content, true);
        $this->assertContains('Created', $content, true);
        $this->assertContains('Due', $content, true);
        $this->assertContains('Actions', $content, true);
        $this->assertContains('New', $content, true);

        //fwrite(STDERR, print_r($content, TRUE));

        $crawler = $client->click($crawler->selectLink('New')->link());

        // Fill in the form and submit it
        $form = $crawler->selectButton('Create')->form(array(
            'todo[todo]'  => '1234567Test',
        ));

        $client->submit($form);
        $crawler = $client->followRedirect();

        // Check data in the show view
        $this->assertGreaterThan(0, $crawler->filter('tr:contains("1234567Test")')->count(),
            'Missing element td:contains("1234567Test")');

        // Edit the entity
        $crawler = $client->click($crawler->filter('tr:contains("1234567Test")')->selectLink('Edit')->link());

        $form = $crawler->selectButton('Store')->form(array(
            'todo[todo]'  => 'Foo1234567',
        ));

        $client->submit($form);
        $crawler = $client->followRedirect();

        // Check the element contains an attribute with value equals "Foo"
        $this->assertGreaterThan(0, $crawler->filter('tr:contains("Foo1234567")')->count(),
            'Missing element [value="Foo1234567"]');

        // Set the entity to Done
        $client->submit($crawler->filter('tr:contains("Foo1234567")')->selectButton('Done')->form());
        $crawler = $client->followRedirect();
        $class = $crawler->filter('tr:contains("Foo1234567")')->attr('class');
        $this->assertContains('done', $class, true);

        // Delete the entity
        $client->submit($crawler->filter('tr:contains("Foo1234567")')->selectButton('Delete')->form());
        $crawler = $client->followRedirect();

        // Check the entity has been delete on the list
        $this->assertNotRegExp('/Foo1234567/', $client->getResponse()->getContent());

    }
}
