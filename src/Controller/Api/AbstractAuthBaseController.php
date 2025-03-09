<?php

namespace App\Controller\Api;

use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\Security\Core\User\UserInterface;

abstract class AbstractAuthBaseController extends AbstractBaseController
{
	protected EntityManagerInterface $entityManager;
	protected Security $security;

	public function __construct(EntityManagerInterface $entityManager, Security $security)
	{
		$this->entityManager = $entityManager;
		$this->security = $security;

		$this->applyUserFilter();
	}

	public function getCurrentUser():?User
	{
		return $this->entityManager->getRepository(User::class)->findOneBy(['id' => $this->security->getUser()->getId()]);
	}

	protected function applyUserFilter(): void
	{
		$userInterface = $this->security->getUser();

		if ($userInterface) {
			$filter = $this->entityManager->getFilters()->enable('user_filter');
			$filter->setParameter('user_id', $userInterface->getId());
		}
	}
}
