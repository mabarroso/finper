<?php

namespace App\Tests\Controller\Api;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

final class HomeControllerTest extends WebTestCase
{
    public function testIndex(): void
    {
        $client = self::createClient();
        $client->request('GET', '/api/');

        self::assertResponseIsSuccessful();
    }
}
