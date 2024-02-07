<?php

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class ApiControllerTest extends WebTestCase
{
    public function testAddToCart()
    {
        $client = static::createClient();

        $client->request('POST', '/items', [], [], [], json_encode(['item' => 'item1']));
        $response = $client->getResponse();

        $this->assertEquals(Response::HTTP_OK, $response->getStatusCode());
        $this->assertJson($response->getContent());

        $data = json_decode($response->getContent(), true);
        $this->assertEquals('Item added to the shopping cart', $data['message']);
        $this->assertEquals('item1', $data['item']);
    }

    public function testViewCart()
    {
        $client = static::createClient();

        $client->request('GET', '/items');
        $response = $client->getResponse();

        $this->assertEquals(Response::HTTP_OK, $response->getStatusCode());
        $this->assertJson($response->getContent());
    }

    public function testUpdateCart()
    {
        $client = static::createClient();

        $client->request('PUT', '/items/item1', [], [], [], json_encode(['newItem' => 'item8']));
        $response = $client->getResponse();

        $this->assertEquals(Response::HTTP_OK, $response->getStatusCode());
        $this->assertJson($response->getContent());

        $data = json_decode($response->getContent(), true);
        $this->assertEquals('Item updated in the shopping cart', $data['message']);
        $this->assertEquals('item8', $data['newItem']);
    }

    public function testRemoveFromCart()
    {
        $client = static::createClient();

        $client->request('DELETE', '/items/item1');
        $response = $client->getResponse();

        $this->assertEquals(Response::HTTP_OK, $response->getStatusCode());
        $this->assertJson($response->getContent());

        $data = json_decode($response->getContent(), true);
        $this->assertEquals('Item removed from the shopping cart', $data['message']);
    }
}
