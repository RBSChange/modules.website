<?php
/**
 * The following attributes are supported:
 * - perm: permissionName to have. Full permissionName without "modules_" prefix
 * - notperm: permissionName to not have. Full permissionName without "modules_" prefix
 * - nodeId: node on which to check permission. Default root folder from the module deduced from permission name.
 * - mode: front, back or all. Default front.
 * At least perm or notperm attributes must be defined.
 */
class PHPTAL_Php_Attribute_CHANGE_permission extends PHPTAL_Php_Attribute
{
	public function start()
	{
		$expressions = $this->tag->generator->splitExpression($this->expression);

		$permission = null;
		$nodeId = null;
		$mode = null;

		foreach ($expressions as $exp)
		{
			list($attribute, $value) = $this->parseSetExpression($exp);
			//echo $attribute." => ".$value."<br>";
			switch ($attribute)
			{
				case 'perm': $permission = $value; break;
				case 'notperm': $notPermission = $value; break;
				case 'nodeId': $nodeId = $this->evaluate($value); break;
				case 'mode' : $mode = $value; break;
			}
		}

		if ($mode === null)
		{
			$mode = "front";
		}

		$permissionInfo = explode(".", $permission);
		if ($nodeId === null)
		{
			$nodeId = ModuleService::getInstance()->getRootFolderId($permissionInfo[0]);
		}

		if ($permission === null && $notPermission === null)
		{
			$this->tag->generator->doEchoRaw("'<strong>change:permission</strong>: you must define perm or notperm attribute'");
			$assertionCode = 'true';
		}
		elseif ($permission !== null)
		{
			if ($mode == "front")
			{
				$assertionCode = '($user = users_FrontenduserService::getInstance()->getCurrentFrontEndUser()) !== null && f_permission_PermissionService::getInstance()->hasFrontEndPermission($user, "modules_'.$permission.'", '.$nodeId.')';
			}
			elseif ($mode == "back")
			{
				$assertionCode = '($user = users_FrontenduserService::getInstance()->getCurrentBackEndUser()) !== null && f_permission_PermissionService::getInstance()->hasPermission($user, "modules_'.$permission.'", '.$nodeId.')';
			}
			else
			{
				throw new Exception("Unsupported mode ".$mode);
			}
		}
		elseif ($notPermission !== null)
		{
			if ($mode == "front")
			{
				$assertionCode = '($user = users_FrontenduserService::getInstance()->getCurrentFrontEndUser()) === null || !f_permission_PermissionService::getInstance()->hasFrontEndPermission($user, "modules_'.$notPermission.'", '.$nodeId.')';
			}
			elseif ($mode == "back")
			{
				$assertionCode = '($user = users_FrontenduserService::getInstance()->getCurrentBackEndUser()) === null || !f_permission_PermissionService::getInstance()->hasPermission($user, "modules_'.$notPermission.'", '.$nodeId.')';
			}
			else
			{
				throw new Exception("Unsupported mode ".$mode);
			}
		}
		$this->tag->generator->doIf($assertionCode);
	}

	public function end()
	{
		$this->tag->generator->doEnd();
	}
}