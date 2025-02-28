<?php

namespace App\Entity;

use App\Repository\RevenueRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: RevenueRepository::class)]
class Revenue
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\ManyToOne]
    #[ORM\JoinColumn(nullable: false)]
    private ?RevenueCategory $RevenueCategory = null;

    #[ORM\Column(length: 50, nullable: true)]
    private ?string $Name = null;

    #[ORM\Column]
    private ?float $Amount = null;

    #[ORM\Column(type: Types::DATE_MUTABLE)]
    private ?\DateTimeInterface $operation_date = null;

    #[ORM\Column(type: Types::DATE_MUTABLE, nullable: true)]
    private ?\DateTimeInterface $checked_date = null;

    #[ORM\ManyToOne]
    #[ORM\JoinColumn(nullable: false)]
    private ?Account $Account = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getRevenueCategory(): ?RevenueCategory
    {
        return $this->RevenueCategory;
    }

    public function setRevenueCategory(?RevenueCategory $RevenueCategory): static
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

    public function getAmount(): ?float
    {
        return $this->Amount;
    }

    public function setAmount(float $Amount): static
    {
        $this->Amount = $Amount;

        return $this;
    }

    public function getOperationDate(): ?\DateTimeInterface
    {
        return $this->operation_date;
    }

    public function setOperationDate(\DateTimeInterface $operation_date): static
    {
        $this->operation_date = $operation_date;

        return $this;
    }

    public function getCheckedDate(): ?\DateTimeInterface
    {
        return $this->checked_date;
    }

    public function setCheckedDate(?\DateTimeInterface $checked_date): static
    {
        $this->checked_date = $checked_date;

        return $this;
    }

    public function getAccount(): ?Account
    {
        return $this->Account;
    }

    public function setAccount(?Account $Account): static
    {
        $this->Account = $Account;

        return $this;
    }
}
