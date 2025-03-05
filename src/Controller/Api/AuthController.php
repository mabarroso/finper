<?php

namespace App\Controller\Api;

use App\Dto\LoginDto;
use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Firebase\JWT\JWT;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Attribute\MapRequestPayload;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Attribute\Route;

final class AuthController extends AbstractAuthBaseController
{
	private EntityManagerInterface $entityManager;

	public function __construct(EntityManagerInterface $EntityManager)
	{
		$this->entityManager = $EntityManager;
	}

	#[Route('/api/v{_version}/auth/login', requirements: ['_version' => '1'], methods: ['POST'])]
	public function create(Request $request, UserPasswordHasherInterface $passwordHasher, #[MapRequestPayload] LoginDto $dto): JsonResponse
	{
		$user = $this->entityManager->getRepository(User::class)->findOneBy(["email" => $dto->email]);

		if (!$user) {
			return $this->errorForbiddenResponse('528970482d36c8c1');
		}

		if ($passwordHasher->isPasswordValid($user, $dto->password)) {
			$date = date_create(date('Y-m-d'));
			$timestamp = date_add($date, date_interval_create_from_date_string('1 days'));
			$payload = [
				'iss' => $request->getUriForPath(""),
				'aud' => $user->getId(),
				'iat' => time(),
				'exp' => strtotime($timestamp->format('Y-m-d'))
			];

			$jwt = JWT::encode($payload, $_ENV['JWT_SECRET'], 'HS512');
			return $this->json(
				[
					'id'    => $user->getId(),
					'token' => $jwt
				], Response::HTTP_OK);
		} else {
			return $this->errorForbiddenResponse('30948930db39649a');
		}
	}

	#[Route('/api/v{_version}/auth', requirements: ['_version' => '1'], methods: ['PUT'])]
	public function update(): JsonResponse
	{
		// todo refresh current token
	}

	#[Route('/api/v{_version}/auth', requirements: ['_version' => '1'], methods: ['DELETE'])]
	public function destroy(): JsonResponse
	{
		// todo invalidates all tokens
	}
}
