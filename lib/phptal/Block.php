<?php

class PHPTAL_Php_Attribute_CHANGE_Block extends ChangeTalAttribute
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
	
	private static $paramNames = array('name', 'module', 'inheritedParams', 'useCache', 'container');
	
	/**
	 * @param array $params
	 */
	function render($params)
	{
		if (!isset($params['name']))
		{
			throw new Exception(__METHOD__ . " Can not render block with no name!");
		}
		
		$controller = website_BlockController::getInstance();
		$callingAction = $controller->getProcessedAction();
		
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
		
		$callingActionCached = $callingAction->getConfiguration()->isCacheEnabled();
		if (isset($params["outside"]) || $callingActionCached)
		{
			//echo "Render ".$this->moduleName."_".$this->actionName." outside ";
			$inheritedParamNames = isset($params['inheritedParams']) ? explode(",", $params['inheritedParams']) : null;
			$forcedParameters = array();
			foreach ($params as $paramName => $paramValue)
			{
				if (in_array($paramName, self::$paramNames))
				{
					continue;
				}
				$forcedParameters[$paramName] = $paramValue;
			}
			
			if (isset($params['container']))
			{
				if (f_util_StringUtils::isEmpty($params['container']))
				{
					echo $controller->addSubBlock($this->moduleName, $this->actionName, $configParameters, $inheritedParamNames, $forcedParameters);
				}
				else
				{
					echo '<' . $params['container'] .' class="modules-'. $this->moduleName .'-'.  $this->actionName  .' modules-' . $this->moduleName . '">';
					echo $controller->addSubBlock($this->moduleName, $this->actionName, $configParameters, $inheritedParamNames, $forcedParameters);
					echo '</' . $params['container'] .'>';
				}
			}
			else
			{
				echo '<div class="modules-'. $this->moduleName .'-'.  $this->actionName  .' modules-' . $this->moduleName . '">';
				echo $controller->addSubBlock($this->moduleName, $this->actionName, $configParameters, $inheritedParamNames, $forcedParameters);
				echo '</div>';
			}
		}
		else
		{
			$useCache = (isset($params["useCache"]) && $params["useCache"] == "true") || !$callingActionCached;
			//echo "Render inside";
			if (isset($params['container']))
			{
				if (f_util_StringUtils::isEmpty($params['container']))
				{
					$this->executeBlockAction($this->getRequestParameters($params, $this->moduleName), $configParameters, $useCache);
				}
				else
				{
					echo '<' . $params['container'] .' class="modules-'. $this->moduleName .'-'.  $this->actionName  .' modules-' . $this->moduleName . '">';
					$this->executeBlockAction($this->getRequestParameters($params, $this->moduleName), $configParameters, $useCache);
					echo '</' . $params['container'] .'>';
				}
			}
			else
			{
				echo '<div class="modules-'. $this->moduleName .'-'.  $this->actionName  .' modules-' . $this->moduleName . '">';
				$this->executeBlockAction($this->getRequestParameters($params, $this->moduleName), $configParameters, $useCache);
				echo '</div>';
			}
		}
	}
	
	private function getConfigParameters(&$extensionParams)
	{
		$configParameters = array();
		foreach ($extensionParams as $parameterName => $parameterValue)
		{
			if (f_util_StringUtils::beginsWith($parameterName, "__"))
			{
				$configParameters[substr($parameterName, 2)] = f_util_Convert::toString($parameterValue);
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
		$globalRequest = change_Controller::getInstance()->getContext()->getRequest();
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
			if (in_array($parameterName, self::$paramNames))
			{
				continue;
			}
			$parameters[$parameterName] = $parameterValue;
		}
		return array($this->moduleName.'Param' => $parameters);
	}
	
	private function executeBlockAction($parameters, $configParameters, $useCache)
	{
		$controller = website_BlockController::getInstance();
		try
		{
			$controller->processByName($this->moduleName, $this->actionName, new f_mvc_FakeHttpRequest($parameters), $configParameters, $useCache);
		}
		catch (Exception $e)
		{
			Framework::exception($e);
		}
	}
}
