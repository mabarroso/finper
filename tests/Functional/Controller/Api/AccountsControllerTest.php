<?php

namespace App\Tests\Functional\Controller\Api;

use App\Entity\Account;

final class AccountsControllerTest extends ApiTestCase
{

	public function testIndex(): void
	{
		$this->getBrowserClient()->request('GET', '/api/v1/accounts', [], [], ['HTTP_TOKEN' => $this->getApiToken()]);

		self::assertResponseIsSuccessful();
		self::assertResponseHeaderSame('content-type', 'application/json');
		self::assertJsonEquals(
			'[{"id":2,"name":"Bank 2","iban":"*5678"},{"id":1,"name":"Bank 1","iban":"*1234"}]',
			$this->getBrowserClient()->getResponse()->getContent());
	}

	public function testShow(): void
	{
		$this->getBrowserClient()->request('GET', '/api/v1/accounts/2', [], [], ['HTTP_TOKEN' => $this->getApiToken()]);

		self::assertResponseIsSuccessful();
		self::assertResponseHeaderSame('content-type', 'application/json');
		self::assertJsonEquals(
			'{"id":2,"name":"Bank 2","iban":"*5678"}',
			$this->getBrowserClient()->getResponse()->getContent());
	}

	public function testCreate_ok(): void
	{
		// Account does not exist
		{
			$accounts = $this->getEntityManager()->getRepository(Account::class)->findBy(["name" => "bank 3", "iban" => "*9876"]);
			if ($accounts) {
				self::fail("Account to create already exists");
			}
		}

		// Test
		{
			$this->getBrowserClient()->request('POST', '/api/v1/accounts', [], [], ['HTTP_TOKEN' => $this->getApiToken(), 'CONTENT_TYPE' => 'application/json'],
				'{"name": "bank 3","iban": "*9876"}'
			);

			self::assertResponseIsSuccessful();
			self::assertResponseHeaderSame('content-type', 'application/json');
			self::assertJsonEquals(
				'{"status": "ok","message": "Created"}',
				$this->getBrowserClient()->getResponse()->getContent());

			$accounts = $this->getEntityManager()->getRepository(Account::class)->findBy(["name" => "bank 3", "iban" => "*9876"]);
			self::assertEquals(1, count($accounts), "Account not created");
		}

		// Remove test account
		if ($accounts) {
			$this->getEntityManager()->remove($accounts[0]);
			$this->getEntityManager()->flush();
		}
	}

	public function testCreate_already_exists(): void
	{
		// Account exist
		{
			$accounts = $this->getEntityManager()->getRepository(Account::class)->findBy(["name" => "bank 1", "iban" => "*1234"]);
			if (!$accounts) {
				self::fail("Account to create not exists");
			}
		}

		// Test
		{
			$this->getBrowserClient()->request('POST', '/api/v1/accounts', [], [], ['HTTP_TOKEN' => $this->getApiToken(), 'CONTENT_TYPE' => 'application/json'],
				'{"name": "bank 1","iban": "*1234"}'
			);

			self::assertResponseStatusCodeSame(409);
			self::assertResponseHeaderSame('content-type', 'application/json');
			self::assertJsonEquals(
				'{"status":"error","code":"a8eaf194fa45ee07","message":"Conflict"}',
				$this->getBrowserClient()->getResponse()->getContent());

			$accounts = $this->getEntityManager()->getRepository(Account::class)->findBy(["name" => "bank 1", "iban" => "*1234"]);
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

		// Account exist
		{
			$accounts = $this->getEntityManager()->getRepository(Account::class)->findBy(["name" => $name, "iban" => $iban]);
			if ($accounts) {
				self::fail("Account to create exists");
			}
		}

		// Test
		{
			$nameValue = $name ? '"name":"' . $name . '"' : null;
			$ibanValue = $iban ? '"iban":"' . $iban . '"' : null;
			$requestJson = '{' . implode(',', array_filter([$nameValue, $ibanValue])) . '}';
			$this->getBrowserClient()->request('POST', '/api/v1/accounts', [], [], ['HTTP_TOKEN' => $this->getApiToken(), 'CONTENT_TYPE' => 'application/json'],
				$requestJson
			);

			self::assertResponseStatusCodeSame(409);
			self::assertResponseHeaderSame('content-type', 'application/json');
			self::assertJsonEquals(
				'{"status":"error","code":"a8eaf194fa45ee07","message":"Conflict"}',
				$this->getBrowserClient()->getResponse()->getContent());

			$accounts = $this->getEntityManager()->getRepository(Account::class)->findBy(["name" => $name, "iban" => $iban]);
			self::assertEquals(0, count($accounts), "Account not created");
		}
	}

	public function testUpdate_ok(): void
	{
		// Create test account
		{
			$account = new Account();
			$account->setName('To update');
			$account->setIban('1234');
			$this->getEntityManager()->persist($account);
			$this->getEntityManager()->flush();
		}

		// Test
		{
			$this->getBrowserClient()->request('PUT', '/api/v1/accounts/' . $account->getId(), [], [], ['HTTP_TOKEN' => $this->getApiToken(), 'CONTENT_TYPE' => 'application/json'],
				'{"name": "updated","iban": "5678"}'
			);

			self::assertResponseStatusCodeSame(200);
			self::assertResponseHeaderSame('content-type', 'application/json');
			self::assertJsonEquals(
				'{"status":"ok","message":"Updated"}',
				$this->getBrowserClient()->getResponse()->getContent());

			$accounts = $this->getEntityManager()->getRepository(Account::class)->findBy(["name" => "updated", "iban" => "5678"]);
			self::assertEquals(1, count($accounts), "Account not updated");
		}
	}

	public function testDestroy_ok(): void
	{
		// Create test account
		{
			$account = new Account();
			$account->setName('To delete');
			$account->setIban('1234');
			$this->getEntityManager()->persist($account);
			$this->getEntityManager()->flush();
		}

		// Test
		{
			$this->getBrowserClient()->request('DELETE', '/api/v1/accounts/' . $account->getId(), [], [], ['HTTP_TOKEN' => $this->getApiToken()]);

			self::assertResponseStatusCodeSame(200);
			self::assertResponseHeaderSame('content-type', 'application/json');
			self::assertJsonEquals(
				'{"status":"ok","message":"Deleted"}',
				$this->getBrowserClient()->getResponse()->getContent());

			$accounts = $this->getEntityManager()->getRepository(Account::class)->findBy(["name" => "To delete", "iban" => "1234"]);
			self::assertEquals(0, count($accounts), "Account not deleted");
		}
	}
}