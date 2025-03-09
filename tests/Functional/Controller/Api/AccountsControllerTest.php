<?php

namespace App\Tests\Functional\Controller\Api;

use App\Entity\Account;
use Symfony\Component\HttpFoundation\Response;

final class AccountsControllerTest extends ApiTestCase
{

	public function testIndex(): void
	{
		$this->getBrowserClient()->request('GET', '/api/v1/accounts', [], [], ['HTTP_TOKEN' => $this->getApiToken()]);

		self::assertResponseIsSuccessful();
		self::assertResponseHeaderSame('content-type', 'application/json');
		self::assertJsonEquals(
			'[{"id":3,"name":"Bank 2","iban":"*5678"},{"id":2,"name":"Bank 1","iban":"*1234"},{"id":1,"name":"Cash","iban":"cash"}]',
			$this->getBrowserClient()->getResponse()->getContent());
	}

	public function testShow(): void
	{
		$this->getBrowserClient()->request('GET', '/api/v1/accounts/2', [], [], ['HTTP_TOKEN' => $this->getApiToken()]);

		self::assertResponseIsSuccessful();
		self::assertResponseHeaderSame('content-type', 'application/json');
		self::assertJsonEquals(
			'{"id":2,"name":"Bank 1","iban":"*1234"}',
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

			self::assertResponseStatusCodeSame(Response::HTTP_CONFLICT);
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
			['expectedCreated'    => false,
			 'expectedStatusCode' => Response::HTTP_UNPROCESSABLE_ENTITY,
			 'expectedData'       => '{"status":"error","code":"2ccd7f275e20fbd9fb01","message":"Name cannot be empty"}',
			 "name"               => null, "iban" => "*XXXX"],
			['expectedCreated'    => true,
			 'expectedStatusCode' => Response::HTTP_CREATED,
			 'expectedData'       => '{"status":"ok","message":"Created"}',
			 "name"               => 'Bank X', "iban" => null],
			['expectedCreated'    => false,
			 'expectedStatusCode' => Response::HTTP_UNPROCESSABLE_ENTITY,
			 'expectedData'       => '{"status":"error","code":"2ccd7f275e20fbd9fb01","message":"Name cannot be empty"}',
			 "name" => null, "iban" => null],
			['expectedCreated'    => false,
			 'expectedStatusCode' => Response::HTTP_UNPROCESSABLE_ENTITY,
			 'expectedData'       => '{"status":"error","code":"2ccd7f275e20fbd9fb01","message":"Name cannot be longer than 10 characters length"}',
			 "name" => '123456789012345678901234567890', "iban" => "*XXXX"],
			['expectedCreated'    => false,
			 'expectedStatusCode' => Response::HTTP_UNPROCESSABLE_ENTITY,
			 'expectedData'       => '{"status":"error","code":"2ccd7f275e20fbd9fb01","message":"Iban cannot be longer than 24 characters length"}',
			 "name" => 'Bank X', "iban" => "*123456789012345678901234567890"],
			['expectedCreated'    => true,
			 'expectedStatusCode' => Response::HTTP_CREATED,
			 'expectedData'       => '{"status":"ok","message":"Created"}',
			 "name" => 'Bank X', "iban" => "*XXXX"],
		];
	}


	/**
	 * @dataProvider create_bad_dataProvider
	 */
	public function testCreate_bad_data(bool $expectedCreated, int $expectedStatusCode, string $expectedData, ?string $name, ?string $iban): void
	{
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

			self::assertResponseStatusCodeSame($expectedStatusCode);
			self::assertResponseHeaderSame('content-type', 'application/json');
			self::assertJsonEquals(
				$expectedData,
				$this->getBrowserClient()->getResponse()->getContent());

			$accounts = $this->getEntityManager()->getRepository(Account::class)->findBy(["name" => $name, "iban" => $iban]);
			self::assertEquals($expectedCreated ? 1 : 0, count($accounts), "Account not created");
		}
	}

	public function testUpdate_ok(): void
	{
		// Create test account
		{
			$account = new Account();
			$account->setUser($this->getUser());
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

			self::assertResponseStatusCodeSame(Response::HTTP_OK);
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
			$account->setUser($this->getUser());
			$account->setName('To delete');
			$account->setIban('1234');
			$this->getEntityManager()->persist($account);
			$this->getEntityManager()->flush();
		}

		// Test
		{
			$this->getBrowserClient()->request('DELETE', '/api/v1/accounts/' . $account->getId(), [], [], ['HTTP_TOKEN' => $this->getApiToken()]);

			self::assertResponseStatusCodeSame(Response::HTTP_OK);
			self::assertResponseHeaderSame('content-type', 'application/json');
			self::assertJsonEquals(
				'{"status":"ok","message":"Deleted"}',
				$this->getBrowserClient()->getResponse()->getContent());

			$accounts = $this->getEntityManager()->getRepository(Account::class)->findBy(["name" => "To delete", "iban" => "1234"]);
			self::assertEquals(0, count($accounts), "Account not deleted");
		}
	}
}