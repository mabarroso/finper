<?php

namespace App\Tests\Functional\Controller\Api;

final class RevenuesControllerTest extends ApiTestCase
{

	public function testIndex(): void
	{
		$this->getBrowserClient()->request('GET', '/api/v1/revenues', [], [], ['HTTP_TOKEN' => $this->getApiToken()]);

		self::assertResponseIsSuccessful();
		self::assertResponseHeaderSame('content-type', 'application/json');
		self::assertJsonEquals(
			'[{"id": 5,"name": "Febrero","category": {"id": 1,"name": "Habituales"},"subcategory": {"id": 3,"name": "Nómina"},"amount": 980.37,"account": {"id": 1,"name": "Cash"},"operationDate": "2025-02-02","checkedDate": "2025-02-02"},{"id": 4,"name": "Devolución","category": {"id": 2,"name": "Puntuales"},"subcategory": {"id": 4,"name": "IRPF"},"amount": 15.5,"account": {"id": 1,"name": "Cash"},"operationDate": "2025-02-15","checkedDate": "2025-02-15"},{"id": 3,"name": "Febrero","category": {"id": 1,"name": "Habituales"},"subcategory": {"id": 5,"name": "Renta"},"amount": 50.15,"account": {"id": 2,"name": "Bank 1"},"operationDate": "2025-02-02","checkedDate": "2025-02-02"},{"id": 2,"name": "Enero","category": {"id": 1,"name": "Habituales"},"subcategory": {"id": 3,"name": "Nómina"},"amount": 980.37,"account": {"id": 1,"name": "Cash"},"operationDate": "2025-01-31","checkedDate": "2025-01-31"},{"id": 1,"name": "Enero","category": {"id": 1,"name": "Habituales"},"subcategory": {"id": 5,"name": "Renta"},"amount": 50.15,"account": {"id": 2,"name": "Bank 1"},"operationDate": "2025-01-02","checkedDate": "2025-01-02"}]',
			$this->getBrowserClient()->getResponse()->getContent());
	}

}