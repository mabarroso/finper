<?php

namespace App\Security;

use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Firebase\JWT\JWT;
use Firebase\JWT\Key;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Exception\AuthenticationException;
use Symfony\Component\Security\Http\Authenticator\AbstractAuthenticator;
use Symfony\Component\Security\Http\Authenticator\Passport\Badge\UserBadge;
use Symfony\Component\Security\Http\Authenticator\Passport\Credentials\PasswordCredentials;
use Symfony\Component\Security\Http\Authenticator\Passport\Passport;
use Symfony\Component\Security\Http\Authenticator\Passport\SelfValidatingPassport;
use Throwable;

class CustomAuthenticator extends AbstractAuthenticator
{
	private EntityManagerInterface $entityManager;

	public function __construct(EntityManagerInterface $entityManager)
	{
		$this->entityManager = $entityManager;
	}

	public function supports(Request $request): ?bool
	{
		return $request->headers->has('TOKEN');
	}

	public function authenticate(Request $request): Passport
	{
		$apiToken = $request->headers->get('TOKEN');
		if (null === $apiToken) {
			return new Passport(new UserBadge(''), new PasswordCredentials(''));
		}

		try {
			$apiTokenDecoded = JWT::decode($apiToken, new Key($_ENV['JWT_SECRET'], 'HS512'));
			$user = $this->entityManager->getRepository(User::class)->findOneBy(['id' => $apiTokenDecoded->aud]);
			if (!$user) {
				return new Passport(new UserBadge('GUEST'), new PasswordCredentials(''));
			} else {
				return new SelfValidatingPassport(new UserBadge($user->getEmail()));
			}
		} catch (Throwable $th) {
			return new Passport(new UserBadge('GUEST'), new PasswordCredentials(''));
		}
	}

	public function onAuthenticationSuccess(Request $request, TokenInterface $token, string $firewallName): ?Response
	{
		// on success, let the request continue
		return null;
	}

	public function onAuthenticationFailure(Request $request, AuthenticationException $exception): ?Response
	{
		$data = [
			// you may want to customize or obfuscate the message first
			'message' => strtr($exception->getMessageKey(), $exception->getMessageData())

			// or to translate this message
			// $this->translator->trans($exception->getMessageKey(), $exception->getMessageData())
		];

		return new JsonResponse($data, Response::HTTP_UNAUTHORIZED);
	}
}