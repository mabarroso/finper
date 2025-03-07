<?php

namespace App\Tests\Functional\Controller\Api;

use App\Entity\User;
use Symfony\Component\HttpFoundation\Response;

final class AuthControllerTest extends ApiTestCase
{

	public function login_dataProvider(): array
	{
		return [
			['expectedStatusCode' => Response::HTTP_FORBIDDEN,
			 'expectedData'       => '{"status":"error","code":"528970482d36c8c1","message":"Forbidden"}',
			 "loginEmail"         => 'bademail@mailinator.com',
			 "loginPassword"      => 'demo',
			],
			['expectedStatusCode' => Response::HTTP_FORBIDDEN,
			 'expectedData'       => '{"status":"error","code":"30948930db39649a","message":"Forbidden"}',
			 "loginEmail"         => 'demo@mailinator.com',
			 "loginPassword"      => 'badpassword',
			],
			['expectedStatusCode' => Response::HTTP_OK,
			 'expectedData'       => '',
			 "loginEmail"         => 'demo@mailinator.com',
			 "loginPassword"      => 'demo',
			],
		];
	}

	/**
	 * @dataProvider login_dataProvider
	 */
	public function testLogin(int $expectedStatusCode, string $expectedData, string $loginEmail, string $loginPassword): void
	{
		$this->getBrowserClient()->request('POST', '/api/v1/auth/login', [], [], ['CONTENT_TYPE' => 'application/json'],
			sprintf('{"email": "%s","password": "%s"}', $loginEmail, $loginPassword)
		);

		if ($expectedStatusCode == Response::HTTP_OK) {
			self::assertResponseIsSuccessful();
		} else {
			self::assertResponseStatusCodeSame($expectedStatusCode);
			self::assertResponseHeaderSame('content-type', 'application/json');
			self::assertJsonEquals(
				$expectedData,
				$this->getBrowserClient()->getResponse()->getContent());
		}
	}
}