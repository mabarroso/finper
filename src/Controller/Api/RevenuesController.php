<?php

namespace App\Controller\Api;

use App\Entity\Revenue;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class RevenuesController extends AbstractAuthBaseController
{
	private EntityManagerInterface $entityManager;


	public function __construct(EntityManagerInterface $EntityManager)
	{
		$this->entityManager = $EntityManager;
	}

	#[Route('/api/v{_version}/revenues', requirements: ['_version' => '1'], methods: ['GET'])]
	public function index(): JsonResponse
	{
		$data = [];

		$revenues = $this->entityManager->getRepository(Revenue::class)->findBy([], ['id' => 'desc']);
		foreach ($revenues as $revenue) {
			$data[] = [
				'id'          => $revenue->getId(),
				'name'        => $revenue->getName(),
				'category'    => $revenue->getRevenueCategory()->getRevenueCategory()->getName(),
				'subcategory' => $revenue->getRevenueCategory()->getName(),
			];
		}
		return $this->json($data, Response::HTTP_OK);
	}
}
