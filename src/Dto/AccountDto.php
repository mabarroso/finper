<?php

namespace App\Dto;

use Symfony\Component\Validator\Constraints as Assert;

readonly class AccountDto
{
	public function __construct(
		#[Assert\NotBlank(message: 'Name cannot be empty')]
		#[Assert\Length(
			min: 1,
			max: 10,
			minMessage: 'Name cannot be empty',
			maxMessage: 'Name cannot be longer than {{ limit }} characters length',
		)]
		#[Assert\Type('string')]
		public ?string $name,

		#[Assert\Length(
			max: 24,
			maxMessage: 'Iban cannot be longer than {{ limit }} characters length',
		)]
		#[Assert\Type('string')]
		public ?string $iban,
	)
	{

	}
}