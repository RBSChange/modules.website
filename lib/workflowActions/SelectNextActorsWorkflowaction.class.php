<?php

class website_SelectNextActorsWorkflowaction extends workflow_BaseWorkflowaction
{
	/**
	 * This method will execute the action.
	 * @return boolean true if the execution end successfully, false in error case.
	 */
	function execute()
	{
		$role = $this->getWorkitem()->getTransition()->getRoleid();
		if (is_null($role))
		{
			$role = 'Validator';
		}	
		$addSuperAdmin = $this->getCaseParameter('AFFECT_TASKS_TO_SUPER_ADMIN') == 'true';
		$users = $this->getActors($this->getDocumentId(), $role, $addSuperAdmin);
		if (count($users) > 0)
		{
			$actorsIds = DocumentHelper::getIdArrayFromDocumentArray($users);
			$this->setCaseParameter('__NEXT_ACTORS_IDS', $actorsIds);
			
			$backenduser = users_UserService::getInstance()->getCurrentBackEndUser();
			if ($backenduser !== null)
			{
				foreach ($users as $user) 
				{
					if (DocumentHelper::equals($backenduser, $user))
					{
						$this->setExecutionStatus('AUTO');
						return true;
					}
				}
			}
		}
		else
		{
			$this->setCaseParameter('__NEXT_ACTORS_IDS', array());
		}
		
		$this->setExecutionStatus('FOUNDED');
		return true;
	}
	
	/**
	 * @param Integer $id
	 * @param string $roleName
	 * @param boolean $addSuperAdmin
	 * @return array<users_persistentdocument_user>
	 */
	private function getActors($id, $roleName, $addSuperAdmin)
	{
		$permissionService = change_PermissionService::getInstance();
		$document = DocumentHelper::getByCorrectionId($id);
		$originalId = $document->getId();
		$roleName = $permissionService->resolveRole($roleName, $originalId);
		
		$actorsIds = $permissionService->getUsersByRoleAndDocumentId($roleName, $originalId);
		if ($addSuperAdmin)
		{
			$rootUsers = users_BackenduserService::getInstance()->getRootUsers();
			foreach ($rootUsers as $rootUser) 
			{
				$rootUserId = $rootUser->getId();
				if (!in_array($rootUserId, $actorsIds))
				{
					$actorsIds[] = $rootUserId;
				}
			}
		}
		
		// If there are user ids, instanciate them.
		$users = array();
		foreach ($actorsIds as $actorId)
		{
			try 
			{
				$user = DocumentHelper::getDocumentInstance($actorId);
				if ($user->isPublished())
				{
					$users[] = $user;
				}
			}
			catch (Exception $e)
			{
				Framework::exception($e);
			}
		}
		
		return $users;
	}
}