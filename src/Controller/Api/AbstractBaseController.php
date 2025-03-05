<?php

namespace App\Controller\Api;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;

abstract class AbstractBaseController extends AbstractController
{

	protected function errorNotFoundResponse(string $code = 'd37e1ccf55d76c5d', array $data = []): JsonResponse
	{
		$data['status'] = 'error';
		$data['code'] = $code;
		$data['message'] = $data['message'] ?? 'Not Found';
		return $this->json($data, Response::HTTP_NOT_FOUND);
	}

	protected function errorBadRequestResponse(string $code = '9f984d147f15e395', array $data = []): JsonResponse
	{
		$data['status'] = 'error';
		$data['code'] = $code;
		$data['message'] = $data['message'] ?? 'Bad Request';
		return $this->json($data, Response::HTTP_BAD_REQUEST);
	}

	protected function errorConflictResponse(string $code = 'f4317cc85d531cc8', array $data = []): JsonResponse
	{
		$data['status'] = 'error';
		$data['code'] = $code;
		$data['message'] = $data['message'] ?? 'Conflict';
		return $this->json($data, Response::HTTP_CONFLICT);
	}

	protected function errorForbiddenResponse(string $code = 'a381a51c58c12a9a', array $data = []): JsonResponse
	{
		$data['status'] = 'error';
		$data['code'] = $code;
		$data['message'] = $data['message'] ?? 'Forbidden';
		return $this->json($data, Response::HTTP_FORBIDDEN);
	}

}
