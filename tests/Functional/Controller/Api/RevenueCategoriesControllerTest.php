<?php

namespace App\Tests\Functional\Controller\Api;

final class RevenueCategoriesControllerTest extends ApiTestCase
{

	public function testIndex(): void
	{
		$this->getBrowserClient()->request('GET', '/api/v1/revenue/categories', [], [], ['HTTP_TOKEN' => $this->getApiToken()]);

		self::assertResponseIsSuccessful();
		self::assertResponseHeaderSame('content-type', 'application/json');
		self::assertJsonEquals(
			'[{"id": 5,"name": "Renta","parent": "Habituales"},{"id": 4,"name": "IRPF","parent": "Puntuales"},{"id": 3,"name": "Nómina","parent": "Habituales"},{"id": 2,"name": "Puntuales","parent": ""},{"id": 1,"name": "Habituales","parent": ""}]',
			$this->getBrowserClient()->getResponse()->getContent());
	}

}