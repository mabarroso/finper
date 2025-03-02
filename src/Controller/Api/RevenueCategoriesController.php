<?php

namespace App\Controller\Api;

use App\Entity\RevenueCategory;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class RevenueCategoriesController extends AbstractAuthBaseController
{
	private EntityManagerInterface $entityManager;

	public function __construct(EntityManagerInterface $EntityManager)
	{
		$this->entityManager = $EntityManager;
	}

	#[Route('/api/v{_version}/revenue/categories', requirements: ['_version' => '1'], methods: ['GET'])]
	public function index(): JsonResponse
	{
		$data = [];

		$categories = $this->entityManager->getRepository(RevenueCategory::class)->findBy([], ['id' => 'desc']);
		foreach ($categories as $category) {
			$data[] = [
				'id'     => $category->getId(),
				'name'   => $category->getName(),
				'parent' => ($category->getRevenueCategory()) ? $category->getRevenueCategory()->getName() : '',
			];
		}
		return $this->json($data, Response::HTTP_OK);
	}
}
