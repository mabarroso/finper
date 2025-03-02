<?php

namespace App\DataFixtures;

use App\Entity\Account;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class AccountFixture extends Fixture
{
	public const REFERENCE = 'Account';
	private array $accounts = [
		['id' => 1, 'iban' => '*1234', 'name' => 'Bank 1'],
		['id' => 2, 'iban' => '*5678', 'name' => 'Bank 2'],
	];

	public function load(ObjectManager $manager): void
	{
		foreach ($this->accounts as $accountData) {
			$account = new Account();
			$account->setIban($accountData['iban']);
			$account->setName($accountData['name']);
			$manager->persist($account);
			$manager->flush();

			$this->addReference(self::REFERENCE . $accountData['id'], $account);
		}
	}
}
