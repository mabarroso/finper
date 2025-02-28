<?php

namespace App\Controller\Api;

use App\Entity\Account;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class AccountsController extends AbstractController
{
	private EntityManagerInterface $entityManager;


	public function __construct(EntityManagerInterface $EntityManager)
	{
		$this->entityManager = $EntityManager;
	}

	#[Route('/api/v{_version}/accounts', requirements: ['_version' => '1'], methods: ['GET'])]
	public function index(): JsonResponse
	{
		$data = [];

		$accounts = $this->entityManager->getRepository(Account::class)->findBy([], ['id' => 'desc']);
		foreach ($accounts as $account) {
			$data[] = [
				'id'          => $account->getId(),
				'name'        => $account->getName(),
			];
		}
		return $this->json($data, Response::HTTP_OK);
	}
}
