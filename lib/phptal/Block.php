<?php

class PHPTAL_Php_Attribute_CHANGE_block extends ChangeTalAttribute
{
	/**
	 * @see ChangeTalAttribute::evaluateAll()
	 *
	 * @return Boolean
	 */
	protected function evaluateAll()
	{
		return true;
	}
	
	static function renderBlock($params)
	{
		$renderer = new website_ChangeBlockRenderer();
		$renderer->render($params);
	}
}


class website_ChangeBlockRenderer
{
	private $moduleName;
	private $actionName;
	/**
	 * @return website_Action
	 */
	function getCallingAction()
	{
		return website_BlockController::getInstance()->getProcessedAction();
	}
	
	/**
	 * @return website_Action
	 */
	function getCallingActionRequest()
	{
		return website_BlockController::getInstance()->getProcessedAction();
	}
	

	/**
	 * @param array $params
	 */
	function render($params)
	{
		if (!isset($params['name']))
		{
			throw new Exception(__METHOD__ . " Can not render block with no name!");
		}
		
		$callingAction = $this->getCallingAction();
		
		if ($callingAction === null)
		{
			throw new Exception(__METHOD__ . " Can not call change:block outside of controller execution!");
		}
		
		$this->actionName = $params['name'];
		
		if (!isset($params['module']))
		{
			$this->moduleName = $callingAction->getModuleName();
		}
		else
		{
			$this->moduleName = $params['module'];
		}
		echo '<div class="modules-'. $this->moduleName .'-'.  $this->actionName  .' modules-' . $this->moduleName . '">';
		$this->executeBlockAction($this->getRequestParameters($params, $this->moduleName));
		echo '</div>';
	}
	
	/**
	 * @param Array $extensionParams
	 * @param String $moduleName
	 * @return Array
	 */
	private function getRequestParameters($extensionParams, $moduleName)
	{
		$parameters = array();
		$globalRequest = HttpController::getInstance()->getContext()->getRequest();
		if ($globalRequest->hasParameter($moduleName.'Param'))
		{
			$parameters = $globalRequest->getParameter($moduleName.'Param');
		}
		
		if (isset($extensionParams['inheritedParams']))
		{
			$actionRequest = website_BlockController::getInstance()->getRequest();
			foreach (explode(",", $extensionParams['inheritedParams']) as $parameterName)
			{
				$trimmedParameterName = trim($parameterName);
				$parameters[$trimmedParameterName] = $actionRequest->getParameter($trimmedParameterName);
			}
		}
		
		foreach ($extensionParams as $parameterName => $parameterValue)
		{
			if (in_array($parameterName, array('name', 'module', 'inheritedParams')))
			{
				continue;
			}
			$parameters[$parameterName] = $parameterValue;
		}
		return array($this->moduleName.'Param' => $parameters);
	}
	
	private function executeBlockAction($parameters)
	{
		$controller = website_BlockController::getInstance();
		try
		{
			$controller->processByName($this->moduleName, $this->actionName, new f_mvc_FakeHttpRequest($parameters));
		}
		catch (Exception $e)
		{
			Framework::exception($e);
		}
	}
}
