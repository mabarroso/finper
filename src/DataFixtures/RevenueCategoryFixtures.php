<?php

namespace App\DataFixtures;

use App\Entity\RevenueCategory;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class RevenueCategoryFixtures extends Fixture
{
	public const REFERENCE = 'RevenueCategory';

	private array $revenueCategories = [
		['id' => 1, 'parent' => null, 'name' => 'Habituales'],
		['id' => 2, 'parent' => null, 'name' => 'Puntuales'],
		['id' => 3, 'parent' => 1, 'name' => 'Nómina'],
		['id' => 4, 'parent' => 2, 'name' => 'IRPF'],
		['id' => 5, 'parent' => 1, 'name' => 'Renta'],
	];

	public function load(ObjectManager $manager): void
	{
		foreach ($this->revenueCategories as $revenueCategoryData) {
			$revenueCategory = new RevenueCategory();
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
}
