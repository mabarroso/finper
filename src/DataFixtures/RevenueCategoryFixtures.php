<?php

namespace App\DataFixtures;

use App\Entity\RevenueCategory;
use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;

class RevenueCategoryFixtures extends Fixture implements DependentFixtureInterface
{
	public const REFERENCE = 'RevenueCategory';

	private array $revenueCategories = [
		['id' => 1, 'user' => 1, 'parent' => null, 'name' => 'Habituales'],
		['id' => 2, 'user' => 1, 'parent' => null, 'name' => 'Puntuales'],
		['id' => 3, 'user' => 1, 'parent' => 1, 'name' => 'Nómina'],
		['id' => 4, 'user' => 1, 'parent' => 2, 'name' => 'IRPF'],
		['id' => 5, 'user' => 2, 'parent' => 1, 'name' => 'Renta'],
		['id' => 6, 'user' => 3, 'parent' => 1, 'name' => 'Category for user One'],
		['id' => 7, 'user' => 4, 'parent' => 1, 'name' => 'Category for user Two'],
	];

	public function load(ObjectManager $manager): void
	{
		foreach ($this->revenueCategories as $revenueCategoryData) {
			$revenueCategory = new RevenueCategory();
			$revenueCategory->setUser(
				$this->getReference(UserFixture::REFERENCE . $revenueCategoryData['user'], User::class)
			);
			if ($revenueCategoryData['parent']) {
				$revenueCategory->setRevenueCategory(
					$this->getReference(self::REFERENCE . $revenueCategoryData['parent'], RevenueCategory::class)
				);
			}
			$revenueCategory->setName($revenueCategoryData['name']);
			$manager->persist($revenueCategory);
			$manager->flush();

			$this->addReference(self::REFERENCE . $revenueCategoryData['id'], $revenueCategory);
		}
	}

	public function getDependencies(): array
	{
		return [
			UserFixture::class,
		];
	}
}
