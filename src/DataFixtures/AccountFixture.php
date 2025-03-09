<?php

namespace App\DataFixtures;

use App\Entity\Account;
use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;

class AccountFixture extends Fixture implements DependentFixtureInterface
{
	public const REFERENCE = 'Account';
	private array $accounts = [
		['id' => 1, 'user' => 1, 'iban' => 'cash', 'name' => 'Cash'],
		['id' => 2, 'user' => 2, 'iban' => '*1234', 'name' => 'Bank 1'],
		['id' => 3, 'user' => 2, 'iban' => '*5678', 'name' => 'Bank 2'],
		['id' => 4, 'user' => 3, 'iban' => '*3333', 'name' => 'Bank for user One'],
		['id' => 5, 'user' => 4, 'iban' => '*4444', 'name' => 'Bank for user Two'],
	];

	public function load(ObjectManager $manager): void
	{
		foreach ($this->accounts as $accountData) {
			$account = new Account();
			$account->setUser(
				$this->getReference(UserFixture::REFERENCE . $accountData['user'], User::class)
			);
			$account->setIban($accountData['iban']);
			$account->setName($accountData['name']);
			$manager->persist($account);
			$manager->flush();

			$this->addReference(self::REFERENCE . $accountData['id'], $account);
		}
	}

	public function getDependencies(): array
	{
		return [
			UserFixture::class,
		];
	}
}
