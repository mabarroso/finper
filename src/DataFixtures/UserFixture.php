<?php

namespace App\DataFixtures;

use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class UserFixture extends Fixture
{
	public const REFERENCE = 'User';
	private array $accounts = [
		['id' => 1, 'email' => 'demo@mailinator.com', 'name' => 'Demo', 'password' => 'demo', 'roles' => [User::ROLE_USER]],
		['id' => 2, 'email' => 'one@mailinator.com', 'name' => 'One', 'password' => 'one', 'roles' => [User::ROLE_USER]],
		['id' => 3, 'email' => 'two@mailinator.com', 'name' => 'Two', 'password' => 'two', 'roles' => [User::ROLE_USER]],
	];

	private UserPasswordHasherInterface $passwordHasher;

	public function __construct(UserPasswordHasherInterface $userPasswordHasherInterface)
	{
		$this->passwordHasher = $userPasswordHasherInterface;
	}

	public function load(ObjectManager $manager): void
	{
		foreach ($this->accounts as $userData) {
			$user = new User();
			$user->setEmail($userData['email']);
			$user->setPassword($this->passwordHasher->hashPassword($user, $userData['password']));
			$user->setRoles($userData['roles']);
			$user->setPrivateKey(bin2hex(random_bytes(5)));
			$manager->persist($user);
			$manager->flush();

			$this->addReference(self::REFERENCE . $userData['id'], $user);
		}
	}
}
