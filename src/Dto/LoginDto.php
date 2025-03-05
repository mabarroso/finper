<?php

namespace App\Dto;

use Symfony\Component\Validator\Constraints as Assert;

readonly class LoginDto
{
	public function __construct(
		#[Assert\NotBlank(message: 'Email is required')]
		#[Assert\Email(message: 'Email is required')]
		public readonly string $email,
		#[Assert\NotBlank(message: 'Password is required')]
		public readonly string $password
	)
	{

	}
}