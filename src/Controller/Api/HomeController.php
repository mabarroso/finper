<?php

namespace App\Controller\Api;

use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Attribute\Route;

final class HomeController extends AbstractBaseController
{
	private EntityManagerInterface $entityManager;

	public function __construct(EntityManagerInterface $EntityManager)
	{
		$this->entityManager = $EntityManager;

	}

	#[Route('/api', methods: ['GET'])]
	#[Route('/api/', methods: ['GET'])]
    public function index(): JsonResponse
    {
        return $this->json([
            'message' => 'FinPer API'
        ]);
    }

	#[Route('/api/v1', methods: ['GET'])]
	#[Route('/api/v1/', methods: ['GET'])]
	public function indexV1(): JsonResponse
	{
		return $this->json([
			'message' => 'FinPer API v1.0'
		]);
	}
}
