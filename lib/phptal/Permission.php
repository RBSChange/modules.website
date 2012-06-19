<?php
/**
 * The following attributes are supported:
 * - perm: permissionName to have. Full permissionName without "modules_" prefix
 * - notperm: permissionName to not have. Full permissionName without "modules_" prefix
 * - nodeId: node on which to check permission. Default root folder from the module deduced from permission name.
 * - mode: front, back or all. Default front.
 * At least perm or notperm attributes must be defined.
 */
class PHPTAL_Php_Attribute_CHANGE_Permission extends PHPTAL_Php_Attribute
{
	
	/**
	 * Called before element printing.
	 */
	public function before(PHPTAL_Php_CodeWriter $codewriter)
	{
		$expressions = $codewriter->splitExpression($this->expression);
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
				case 'nodeId': $nodeId = $codewriter->evaluateExpression($value); break;
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
			$codewriter->doEchoRaw("'<strong>change:permission</strong>: you must define perm or notperm attribute'");
			$assertionCode = 'true';
		}
		elseif ($permission !== null)
		{
			if ($mode == "front")
			{
				$assertionCode = '($user = users_FrontenduserService::getInstance()->getCurrentFrontEndUser()) !== null && change_PermissionService::getInstance()->hasFrontEndPermission($user, "modules_'.$permission.'", '.$nodeId.')';
			}
			elseif ($mode == "back")
			{
				$assertionCode = '($user = users_FrontenduserService::getInstance()->getCurrentBackEndUser()) !== null && change_PermissionService::getInstance()->hasPermission($user, "modules_'.$permission.'", '.$nodeId.')';
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
				$assertionCode = '($user = users_FrontenduserService::getInstance()->getCurrentFrontEndUser()) === null || !change_PermissionService::getInstance()->hasFrontEndPermission($user, "modules_'.$notPermission.'", '.$nodeId.')';
			}
			elseif ($mode == "back")
			{
				$assertionCode = '($user = users_FrontenduserService::getInstance()->getCurrentBackEndUser()) === null || !change_PermissionService::getInstance()->hasPermission($user, "modules_'.$notPermission.'", '.$nodeId.')';
			}
			else
			{
				throw new Exception("Unsupported mode ".$mode);
			}
		}
		$codewriter->doIf($assertionCode);
	}

	/**
	 * Called after element printing.
	 */
	public function after(PHPTAL_Php_CodeWriter $codewriter)
	{
		$codewriter->doEnd();
	}
}