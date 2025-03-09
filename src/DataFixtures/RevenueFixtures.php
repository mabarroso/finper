<?php

namespace App\DataFixtures;

use App\Entity\Account;
use App\Entity\Revenue;
use App\Entity\RevenueCategory;
use DateTime;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;

class RevenueFixtures extends Fixture implements DependentFixtureInterface
{
	public const REFERENCE = 'Revenue';

	private array $revenues = [
		['id' => 1, 'category' => 5, 'account' => 2, 'name' => 'Enero', 'amount' => 50.15, 'operation_date' => '2025-01-02', 'checked_date' => '2025-01-02'],
		['id' => 2, 'category' => 3, 'account' => 1, 'name' => 'Enero', 'amount' => 980.37, 'operation_date' => '2025-01-31', 'checked_date' => '2025-01-31'],
		['id' => 3, 'category' => 5, 'account' => 2, 'name' => 'Febrero', 'amount' => 50.15, 'operation_date' => '2025-02-02', 'checked_date' => '2025-02-02'],
		['id' => 4, 'category' => 4, 'account' => 1, 'name' => 'Devolución', 'amount' => 15.5, 'operation_date' => '2025-02-15', 'checked_date' => '2025-02-15'],
		['id' => 5, 'category' => 3, 'account' => 1, 'name' => 'Febrero', 'amount' => 980.37, 'operation_date' => '2025-02-02', 'checked_date' => '2025-02-02'],
	];

	public function load(ObjectManager $manager): void
	{
		foreach ($this->revenues as $revenueData) {
			$revenue = new Revenue();
			$revenue->setRevenueCategory(
				$this->getReference(RevenueCategoryFixtures::REFERENCE . $revenueData['category'], RevenueCategory::class)
			);
			$revenue->setAccount(
				$this->getReference(AccountFixture::REFERENCE . $revenueData['account'], Account::class)
			);
			$revenue->setName($revenueData['name']);
			$revenue->setAmount($revenueData['amount']);
			$revenue->setOperationDate(DateTime::createFromFormat('Y-m-d', $revenueData['operation_date']));
			$revenue->setCheckedDate(DateTime::createFromFormat('Y-m-d', $revenueData['checked_date']));
			$manager->persist($revenue);
			$manager->flush();

			$this->addReference(self::REFERENCE . $revenueData['id'], $revenue);
		}
	}

	public function getDependencies(): array
	{
		return [
			AccountFixture::class,
			RevenueCategoryFixtures::class,
		];
	}
}
