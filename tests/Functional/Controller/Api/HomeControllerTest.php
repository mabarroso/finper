<?php

namespace App\Tests\Functional\Controller\Api;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

final class HomeControllerTest extends WebTestCase
{

	/**
	 * @dataProvider urlsProvider
	 */
    public function testIndex($expectedCode, $url): void
    {
        $client = self::createClient();
        $client->request('GET', $url);

        self::assertResponseStatusCodeSame($expectedCode);
    }

	public function urlsProvider(): array
	{
		return [
			[200, '/api'],
			[301, '/api/'],
			[200, '/api/v1'],
		];
	}
}
