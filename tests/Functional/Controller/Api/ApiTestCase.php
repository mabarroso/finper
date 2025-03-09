<?php

namespace App\Tests\Functional\Controller\Api;

use App\Entity\User;
use Firebase\JWT\JWT;
use PHPUnit\Framework\ExpectationFailedException;
use Symfony\Bundle\FrameworkBundle\KernelBrowser;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

abstract class ApiTestCase extends WebTestCase
{
	private ?KernelBrowser $browserClient = null;
	private ?object $entityManager = null;

	public function getBrowserClient(): KernelBrowser
	{
		if (!$this->browserClient) {
			$this->browserClient = self::createClient();
			$this->entityManager = $this->browserClient->getContainer()->get('doctrine.orm.entity_manager');
		}
		return $this->browserClient;
	}

	public function getEntityManager(): object
	{
		if (!$this->entityManager) {
			$this->entityManager = $this->getBrowserClient()->getContainer()->get('doctrine.orm.entity_manager');
		}
		return $this->entityManager;
	}

	/**
	 * Asserts that two json are equal.
	 *
	 * @throws \SebastianBergmann\RecursionContext\InvalidArgumentException
	 * @throws ExpectationFailedException
	 */
	public static function assertJsonEquals(string $expected, string $actual): void
	{
		$data = json_decode($actual, true);
		self::assertEquals(json_decode($expected, true), $data);
	}

	public function getApiToken(int $userId = null, string $date = null, string $expiration = null): string
	{
		if (!$userId) {
			$userId = $this->getUser()->getId();
		}

		$date = $date ?? date_create(date('Y-m-d'));
		$timestamp = date_add($date, date_interval_create_from_date_string('1 days'));
		$expiration = $expiration ?? strtotime($timestamp->format('Y-m-d'));

		$payload = [
			'iss' => 'test',
			'aud' => $userId,
			'iat' => time(),
			'exp' => $expiration
		];

		return JWT::encode($payload, $_ENV['JWT_SECRET'], 'HS512');
	}

	protected function getUser(): User
	{
		return $this->getEntityManager()->getRepository(User::class)->findOneBy(["email" => 'demo@mailinator.com']);
	}
}
