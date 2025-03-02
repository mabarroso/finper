<?php

namespace App\Tests\Functional\Controller\Api;

use App\Entity\Account;
use PHPUnit\Framework\ExpectationFailedException;

final class AccountsControllerTest extends ApiTestCase
{

	public function testIndex(): void
	{
		$client = self::createClient();
		$client->request('GET', '/api/v1/accounts');

		self::assertResponseIsSuccessful();
		self::assertResponseHeaderSame('content-type', 'application/json');
		self::assertJsonEquals(
			'[{"id":2,"name":"Bank 2","iban":"*5678"},{"id":1,"name":"Bank 1","iban":"*1234"}]',
			$client->getResponse()->getContent());
	}

	public function testShow(): void
	{
		$client = self::createClient();
		$client->request('GET', '/api/v1/accounts/2');

		self::assertResponseIsSuccessful();
		self::assertResponseHeaderSame('content-type', 'application/json');
		self::assertJsonEquals(
			'{"id":2,"name":"Bank 2","iban":"*5678"}',
			$client->getResponse()->getContent());
	}

	public function testCreate_ok(): void
	{
		$client = self::createClient();
		$entityManager = $client->getContainer()->get('doctrine.orm.entity_manager');

		// Account does not exist
		{
			$accounts = $entityManager->getRepository(Account::class)->findBy(["name" => "bank 3", "iban" => "*9876"]);
			if ($accounts) {
				self::fail("Account to create already exists");
			}
		}

		// Test
		{
			$client->request('POST', '/api/v1/accounts', [], [], ['CONTENT_TYPE' => 'application/json'],
				'{"name": "bank 3","iban": "*9876"}'
			);

			self::assertResponseIsSuccessful();
			self::assertResponseHeaderSame('content-type', 'application/json');
			self::assertJsonEquals(
				'{"status": "ok","message": "Created"}',
				$client->getResponse()->getContent());

			$accounts = $entityManager->getRepository(Account::class)->findBy(["name" => "bank 3", "iban" => "*9876"]);
			self::assertEquals(1, count($accounts), "Account not created");
		}

		// Remove test account
		if ($accounts) {
			$entityManager->remove($accounts[0]);
			$entityManager->flush();
		}
	}

	public function testCreate_already_exists(): void
	{
		$client = self::createClient();
		$entityManager = $client->getContainer()->get('doctrine.orm.entity_manager');

		// Account exist
		{
			$accounts = $entityManager->getRepository(Account::class)->findBy(["name" => "bank 1", "iban" => "*1234"]);
			if (!$accounts) {
				self::fail("Account to create not exists");
			}
		}

		// Test
		{
			$client->request('POST', '/api/v1/accounts', [], [], ['CONTENT_TYPE' => 'application/json'],
				'{"name": "bank 1","iban": "*1234"}'
			);

			self::assertResponseStatusCodeSame(409);
			self::assertResponseHeaderSame('content-type', 'application/json');
			self::assertJsonEquals(
				'{"status":"error","code":"a8eaf194fa45ee07","message":"Conflict"}',
				$client->getResponse()->getContent());

			$accounts = $entityManager->getRepository(Account::class)->findBy(["name" => "bank 1", "iban" => "*1234"]);
			self::assertEquals(1, count($accounts), "Account created (duplicated)");
		}
	}

	public function create_bad_dataProvider(): array
	{
		return [
			["name" => null, "iban" => "*XXXX"],
			["name" => 'Bank X', "iban" => null],
			["name" => null, "iban" => null],
			["name" => '123456789012345678901234567890', "iban" => "*XXXX"],
			["name" => 'Bank X', "iban" => "*123456789012345678901234567890"],
			["name" => 'Bank X', "iban" => "*XXXX"],
		];
	}

	/**
	 * @dataProvider create_bad_dataProvider
	 */
	public function testCreate_bad_data(?string $name, ?string $iban): void
	{
		self::markTestSkipped('TODO Return validation results correctly');

		$client = self::createClient();
		$entityManager = $client->getContainer()->get('doctrine.orm.entity_manager');

		// Account exist
		{
			$accounts = $entityManager->getRepository(Account::class)->findBy(["name" => $name, "iban" => $iban]);
			if ($accounts) {
				self::fail("Account to create exists");
			}
		}

		// Test
		{
			$nameValue = $name ? '"name":"' . $name . '"' : null;
			$ibanValue = $iban ? '"iban":"' . $iban . '"' : null;
			$requestJson = '{' . implode(',', array_filter([$nameValue, $ibanValue])) . '}';
			$client->request('POST', '/api/v1/accounts', [], [], ['CONTENT_TYPE' => 'application/json'],
				$requestJson
			);

			self::assertResponseStatusCodeSame(409);
			self::assertResponseHeaderSame('content-type', 'application/json');
			self::assertJsonEquals(
				'{"status":"error","code":"a8eaf194fa45ee07","message":"Conflict"}',
				$client->getResponse()->getContent());

			$accounts = $entityManager->getRepository(Account::class)->findBy(["name" => $name, "iban" => $iban]);
			self::assertEquals(0, count($accounts), "Account not created");
		}
	}

	public function testUpdate_ok(): void
	{
		self::markTestSkipped('To implement ');
	}

	public function testDestroy_ok(): void
	{
		self::markTestSkipped('To implement ');
	}
}