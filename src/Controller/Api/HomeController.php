<?php

namespace App\Controller\Api;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Attribute\Route;

final class HomeController extends AbstractController
{
	#[Route('/api/', name: 'app_api_home', methods: ['GET'])]
    public function index(): JsonResponse
    {
        return $this->json([
            'message' => 'FinPer API v1.0'
        ]);
    }
}
