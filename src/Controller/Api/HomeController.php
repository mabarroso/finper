<?php

namespace App\Controller\Api;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Attribute\Route;

final class HomeController extends AbstractController
{
	#[Route('/api/', methods: ['GET'])]
    public function index(): JsonResponse
    {
        return $this->json([
            'message' => 'FinPer API'
        ]);
    }

	#[Route('/api/v1/', methods: ['GET'])]
	public function indexV1(): JsonResponse
	{
		return $this->json([
			'message' => 'FinPer API v1.0'
		]);
	}
}
