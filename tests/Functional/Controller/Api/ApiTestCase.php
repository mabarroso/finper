<?php

namespace App\Tests\Functional\Controller\Api;

use PHPUnit\Framework\ExpectationFailedException;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

abstract class ApiTestCase extends WebTestCase
{

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
}
