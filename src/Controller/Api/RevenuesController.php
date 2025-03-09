<?php

namespace App\Controller\Api;

use App\Entity\Revenue;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class RevenuesController extends AbstractAuthBaseController
{
	#[Route('/api/v{_version}/revenues', requirements: ['_version' => '1'], methods: ['GET'])]
	public function index(): JsonResponse
	{
		$data = [];

		$revenues = $this->entityManager->getRepository(Revenue::class)->findBy([], ['id' => 'desc']);
		foreach ($revenues as $revenue) {
			$data[] = [
				'id'            => $revenue->getId(),
				'name'          => $revenue->getName(),
				'category'      => $revenue->getRevenueCategory()->getRevenueCategory()->toArray(),
				'subcategory'   => $revenue->getRevenueCategory()->toArray(),
				'amount'        => $revenue->getAmount(),
				'account'       => $revenue->getAccount()->toArray(),
				'operationDate' => $revenue->getOperationDate()->format('Y-m-d'),
				'checkedDate'   => $revenue->getCheckedDate()->format('Y-m-d'),
			];
		}
		return $this->json($data, Response::HTTP_OK);
	}
}
