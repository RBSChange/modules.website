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
	 * @return f_action_BaseAction
	 */
	function getCallingAction()
	{
		return website_BlockController::getInstance()->getProcessedAction();
	}
	
	/**
	 * @return f_action_BaseAction
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
		
		// getConfigParameters() has to be called before getRequestParameters()
		$configParameters = $this->getConfigParameters($params);
		if (isset($params['container']))
		{
			if (f_util_StringUtils::isEmpty($params['container']))
			{
				$this->executeBlockAction($this->getRequestParameters($params, $this->moduleName), $configParameters);
			}
			else
			{
				echo '<' . $params['container'] .' class="modules-'. $this->moduleName .'-'.  $this->actionName  .' modules-' . $this->moduleName . '">';
				$this->executeBlockAction($this->getRequestParameters($params, $this->moduleName));
				echo '</' . $params['container'] .'>';
			}
		}
		else
		{
			echo '<div class="modules-'. $this->moduleName .'-'.  $this->actionName  .' modules-' . $this->moduleName . '">';
			$this->executeBlockAction($this->getRequestParameters($params, $this->moduleName), $configParameters);
			echo '</div>';
		}
	}
	
	private function getConfigParameters(&$extensionParams)
	{
		$configParameters = array();
		foreach ($extensionParams as $parameterName => $parameterValue)
		{
			if (f_util_StringUtils::beginsWith($parameterName, "__"))
			{
				$configParameters[substr($parameterName, 2)] = strval($parameterValue);
				// PHPTal does not like attributes starting with "__"
				unset($extensionParams[$parameterName]);
			}
		}
		return $configParameters;
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
	
	private function executeBlockAction($parameters, $configParameters)
	{
		$controller = website_BlockController::getInstance();
		try
		{
			$controller->processByName($this->moduleName, $this->actionName, new f_mvc_FakeHttpRequest($parameters), $configParameters);
		}
		catch (Exception $e)
		{
			Framework::exception($e);
		}
	}
}
