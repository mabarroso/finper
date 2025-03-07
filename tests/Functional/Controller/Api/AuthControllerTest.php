<?php

namespace App\Tests\Functional\Controller\Api;

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

	public function testRefreshToken_ok(): void
	{
		$this->getBrowserClient()->request('PUT', '/api/v1/auth/refreshToken', [], [], ['HTTP_TOKEN' => $this->getApiToken()]);

		self::assertResponseIsSuccessful();
	}

	public function testRefreshToken_ko_expired_token(): void
	{
		$this->getBrowserClient()->request('PUT', '/api/v1/auth/refreshToken', [], [], ['HTTP_TOKEN' => 'eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzUxMiJ9.eyJpc3MiOiJ0ZXN0IiwiYXVkIjoxLCJpYXQiOjE3NDEzNDY5NjIsImV4cCI6MTc0MTIxOTIwMH0.BeM7HoghuxzdJgfMNsCgRfoIlZ8iyRNPdUyQcbudhZcBFBSPHOD_6gYLQxNqOO77WuCcH8HX4DAA74RhkILg7A']);

		self::assertResponseStatusCodeSame(Response::HTTP_UNAUTHORIZED);
		self::assertResponseHeaderSame('content-type', 'application/json');
		self::assertJsonEquals(
			'{"message":"Invalid credentials."}',
			$this->getBrowserClient()->getResponse()->getContent());
	}

	public function testRefreshToken_ko_bad_token(): void
	{
		$this->getBrowserClient()->request('PUT', '/api/v1/auth/refreshToken', [], [], ['HTTP_TOKEN' => 'bad_token']);

		self::assertResponseStatusCodeSame(Response::HTTP_UNAUTHORIZED);
		self::assertResponseHeaderSame('content-type', 'application/json');
		self::assertJsonEquals(
			'{"message":"Invalid credentials."}',
			$this->getBrowserClient()->getResponse()->getContent());
	}

	public function testRefreshToken_ko_no_token(): void
	{
		$this->getBrowserClient()->request('PUT', '/api/v1/auth/refreshToken');

		self::assertResponseStatusCodeSame(Response::HTTP_UNAUTHORIZED);
		self::assertResponseHeaderSame('content-type', 'application/json');
		self::assertJsonEquals(
			'{"status": "error","code": "2ccd7f275e20fbd9fb01","message": "Full authentication is required to access this resource."}',
			$this->getBrowserClient()->getResponse()->getContent());
	}
}