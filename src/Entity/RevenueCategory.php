<?php

namespace App\Entity;

use App\Repository\RevenueCategoryRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: RevenueCategoryRepository::class)]
class RevenueCategory
{
	#[ORM\Id]
	#[ORM\GeneratedValue]
	#[ORM\Column]
	private ?int $id = null;

	#[ORM\ManyToOne(targetEntity: self::class)]
	private ?self $RevenueCategory = null;

	#[ORM\Column(length: 50, nullable: true)]
	private ?string $Name = null;

    #[ORM\ManyToOne(inversedBy: 'revenueCategories')]
    #[ORM\JoinColumn(nullable: false)]
    private ?User $user = null;

	public function toArray()
	{
		return [
			'id'   => $this->getId(),
			'name' => $this->getName()
		];
	}

	public function getId(): ?int
	{
		return $this->id;
	}

	public function getRevenueCategory(): ?self
	{
		return $this->RevenueCategory;
	}

	public function setRevenueCategory(?self $RevenueCategory): static
	{
		$this->RevenueCategory = $RevenueCategory;

		return $this;
	}

	public function getName(): ?string
	{
		return $this->Name;
	}

	public function setName(?string $Name): static
	{
		$this->Name = $Name;

		return $this;
	}

    public function getUser(): ?User
    {
        return $this->user;
    }

    public function setUser(?User $user): static
    {
        $this->user = $user;

        return $this;
    }
}
