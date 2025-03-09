<?php

namespace App\Doctrine\Filter;

use App\Entity\User;
use Doctrine\ORM\Mapping\ClassMetadata;
use Doctrine\ORM\Mapping\MappingException;
use Doctrine\ORM\Query\Filter\SQLFilter;

class UserFilter extends SQLFilter
{
	public function addFilterConstraint(ClassMetadata $targetEntity, string $targetTableAlias): string
	{
		if ($targetEntity == 'App\Entity\User' || !$this->isAssociatedWithUsers($targetEntity)) {
			return '';
		}

		try {
			$userId = $this->getParameter('user_id');
		} catch (\Exception $e) {
			return '';
		}

		return sprintf('%s.user_id in(%s, %s)', $targetTableAlias, $userId, User::GENERAL_USER_ID);
	}

	protected function isAssociatedWithUsers(ClassMetadata $targetEntity): bool
	{
		try {
			$targetEntity->getAssociationMapping('user');
			return true;
		} catch (MappingException $e) {
			return false;
		}
	}
}