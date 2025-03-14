<?php

namespace App\Entity;

use App\Repository\UserRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Security\Core\User\UserInterface;

#[ORM\Entity(repositoryClass: UserRepository::class)]
#[ORM\UniqueConstraint(name: 'UNIQ_IDENTIFIER_EMAIL', fields: ['email'])]
class User implements UserInterface, PasswordAuthenticatedUserInterface
{
	public const GENERAL_USER_ID = 1;

	public const PUBLIC_ACCESS = 'PUBLIC_ACCESS';
	public const ROLE_USER = 'ROLE_USER';

	#[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 180)]
    private ?string $email = null;

    /**
     * @var list<string> The user roles
     */
    #[ORM\Column]
    private array $roles = [];

    /**
     * @var ?string The hashed password
     */
    #[ORM\Column]
    private ?string $password = null;

    #[ORM\Column(length: 10)]
    private ?string $privateKey = null;

    /**
     * @var Collection<int, Account>
     */
    #[ORM\OneToMany(targetEntity: Account::class, mappedBy: 'user', orphanRemoval: true)]
    private Collection $accounts;

    /**
     * @var Collection<int, RevenueCategory>
     */
    #[ORM\OneToMany(targetEntity: RevenueCategory::class, mappedBy: 'user', orphanRemoval: true)]
    private Collection $revenueCategories;

    /**
     * @var Collection<int, Revenue>
     */
    #[ORM\OneToMany(targetEntity: Revenue::class, mappedBy: 'user', orphanRemoval: true)]
    private Collection $revenues;

    public function __construct()
    {
        $this->accounts = new ArrayCollection();
        $this->revenueCategories = new ArrayCollection();
        $this->revenues = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(string $email): static
    {
        $this->email = $email;

        return $this;
    }

    /**
     * A visual identifier that represents this user.
     *
     * @see UserInterface
     */
    public function getUserIdentifier(): string
    {
        return (string) $this->email;
    }

    /**
     * @see UserInterface
     *
     * @return list<string>
     */
    public function getRoles(): array
    {
        $roles = $this->roles;
        // guarantee every user at least has ROLE_USER
        $roles[] = self::ROLE_USER;

        return array_unique($roles);
    }

    /**
     * @param list<string> $roles
     */
    public function setRoles(array $roles): static
    {
        $this->roles = $roles;

        return $this;
    }

    /**
     * @see PasswordAuthenticatedUserInterface
     */
    public function getPassword(): ?string
    {
        return $this->password;
    }

    public function setPassword(string $password): static
    {
        $this->password = $password;

        return $this;
    }

    /**
     * @see UserInterface
     */
    public function eraseCredentials(): void
    {
        // If you store any temporary, sensitive data on the user, clear it here
        // $this->plainPassword = null;
    }

    public function getPrivateKey(): ?string
    {
        return $this->privateKey;
    }

    public function setPrivateKey(string $privateKey): static
    {
        $this->privateKey = $privateKey;

        return $this;
    }

    /**
     * @return Collection<int, Account>
     */
    public function getAccounts(): Collection
    {
        return $this->accounts;
    }

    public function addAccount(Account $account): static
    {
        if (!$this->accounts->contains($account)) {
            $this->accounts->add($account);
            $account->setUser($this);
        }

        return $this;
    }

    public function removeAccount(Account $account): static
    {
        if ($this->accounts->removeElement($account)) {
            // set the owning side to null (unless already changed)
            if ($account->getUser() === $this) {
                $account->setUser(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, RevenueCategory>
     */
    public function getRevenueCategories(): Collection
    {
        return $this->revenueCategories;
    }

    public function addRevenueCategory(RevenueCategory $revenueCategory): static
    {
        if (!$this->revenueCategories->contains($revenueCategory)) {
            $this->revenueCategories->add($revenueCategory);
            $revenueCategory->setUser($this);
        }

        return $this;
    }

    public function removeRevenueCategory(RevenueCategory $revenueCategory): static
    {
        if ($this->revenueCategories->removeElement($revenueCategory)) {
            // set the owning side to null (unless already changed)
            if ($revenueCategory->getUser() === $this) {
                $revenueCategory->setUser(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, Revenue>
     */
    public function getRevenues(): Collection
    {
        return $this->revenues;
    }

    public function addRevenue(Revenue $revenue): static
    {
        if (!$this->revenues->contains($revenue)) {
            $this->revenues->add($revenue);
            $revenue->setUser($this);
        }

        return $this;
    }

    public function removeRevenue(Revenue $revenue): static
    {
        if ($this->revenues->removeElement($revenue)) {
            // set the owning side to null (unless already changed)
            if ($revenue->getUser() === $this) {
                $revenue->setUser(null);
            }
        }

        return $this;
    }
}
