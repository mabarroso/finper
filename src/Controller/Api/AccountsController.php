<?php

namespace App\Controller\Api;

use App\Dto\AccountDto;
use App\Entity\Account;
use Doctrine\DBAL\Exception\ForeignKeyConstraintViolationException;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Attribute\MapRequestPayload;
use Symfony\Component\Routing\Attribute\Route;

final class AccountsController extends AbstractAuthBaseController
{

	#[Route('/api/v{_version}/accounts', requirements: ['_version' => '1'], methods: ['GET'])]
	public function index(): JsonResponse
	{
		$data = [];

		$accounts = $this->entityManager->getRepository(Account::class)->findBy([], ['id' => 'desc']);
		foreach ($accounts as $account) {
			$data[] = [
				'id'   => $account->getId(),
				'name' => $account->getName(),
				'iban' => $account->getIban(),
			];
		}

		return $this->json($data, Response::HTTP_OK);
	}

	#[Route('/api/v{_version}/accounts/{id}', requirements: ['_version' => '1'], methods: ['GET'])]
	public function show(int $id): JsonResponse
	{
		$account = $this->entityManager->getRepository(Account::class)->find($id);
		if (!$account) {
			return $this->errorNotFoundResponse('e927ea68841b8a3b');
		}

		return $this->json([
			'id'   => $account->getId(),
			'name' => $account->getName(),
			'iban' => $account->getIban(),
		], Response::HTTP_OK);
	}

	#[Route('/api/v{_version}/accounts', requirements: ['_version' => '1'], methods: ['POST'])]
	public function create(#[MapRequestPayload] AccountDto $dto): JsonResponse
	{
		$accounts = $this->entityManager->getRepository(Account::class)->findBy(["name" => $dto->name, "iban" => $dto->iban]);
		if ($accounts) {
			return $this->errorConflictResponse('a8eaf194fa45ee07');
		}

		$account = new Account();
		$account->setUser($this->getCurrentUser());
		$account->setName($dto->name);
		$account->setIban($dto->iban);
		$this->entityManager->persist($account);
		$this->entityManager->flush();

		return $this->json([
			'status'  => 'ok',
			'message' => 'Created'
		], Response::HTTP_CREATED);
	}

	#[Route('/api/v{_version}/accounts/{id}', requirements: ['_version' => '1'], methods: ['PUT'])]
	public function update(#[MapRequestPayload] AccountDto $dto, int $id): JsonResponse
	{
		$account = $this->entityManager->getRepository(Account::class)->find($id);
		if (!$account) {
			return $this->errorNotFoundResponse('1de6b4992dceb12e');
		}
		$account->setName($dto->name);
		$account->setIban($dto->iban);
		$this->entityManager->flush();

		return $this->json([
			'status'  => 'ok',
			'message' => 'Updated'
		], RESPONSE::HTTP_OK);
	}

	#[Route('/api/v{_version}/accounts/{id}', requirements: ['_version' => '1'], methods: ['DELETE'])]
	public function destroy(int $id): JsonResponse
	{
		$account = $this->entityManager->getRepository(Account::class)->find($id);
		if (!$account) {
			return $this->errorNotFoundResponse('36e10fad373b0c02');
		}
		try {
			$this->entityManager->remove($account);
			$this->entityManager->flush();
			return $this->json([
				'status'  => 'ok',
				'message' => 'Deleted'
			], RESPONSE::HTTP_OK);
		} catch (ForeignKeyConstraintViolationException $e) {
			return $this->errorBadRequestResponse('57802b4646d37b85', ['message' => 'Account in use']);
		}
	}
}
