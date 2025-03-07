<?php

namespace App\EventListener;

use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\HttpExceptionInterface;
use Symfony\Component\EventDispatcher\Attribute\AsEventListener;
use Symfony\Component\HttpKernel\Event\ExceptionEvent;

#[AsEventListener]
final class ApiExceptionListener
{
	public function __invoke(ExceptionEvent $event): void
	{
		$exception = $event->getThrowable();

		$data = [
			'status'  => 'error',
			'code'    => '2ccd7f275e20fbd9fb01',
			'message' => $exception->getMessage(),
		];

		if ($exception instanceof HttpExceptionInterface) {
			$status = $exception->getStatusCode();
			$headers = $exception->getHeaders();
		} else {
			$status = Response::HTTP_INTERNAL_SERVER_ERROR;
			$headers = [];
		}

		$apiRsponse = new JsonResponse($data, $status, $headers);

		$event->setResponse($apiRsponse);
	}
}