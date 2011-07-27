<?php
abstract class website_ViewLoadHandlerImpl implements website_ViewLoadHandler
{
	private $params;

	function setParameters($params)
	{
		$this->params = $params;
	}

	protected final function getParameter($name, $defaultValue = null)
	{
		if (isset($this->params[$name]))
		{
			return $this->params[$name];
		}
		return $defaultValue;
	}

	/**
	 * @return f_persistentdocument_PersistentDocument
	 */
	protected final function getDocumentParameter($paramName = K::COMPONENT_ID_ACCESSOR, $expectedClassName = null)
	{
		$value = $this->findLocalParameterValue($paramName);
		if (is_array($value))
		{
			$value = f_util_ArrayUtils::firstElement($value);
		}
		if (is_numeric($value) && $value > 0)
		{
			$doc = DocumentHelper::getDocumentInstance($value);
			if ($expectedClassName !== null && !($doc instanceof $expectedClassName))
			{
				throw new Exception("$paramName parameter does not correspond to any $expectedClassName instance but is a ".get_class($doc));
			}
			return $doc;
		}
		return null;
	}

	/**
	 * @return website_Page
	 */
	protected final function getContext()
	{
		return website_BlockController::getInstance()->getContext();
	}

	/**
	 * @param String $parameterName
	 * @return mixed
	 */
	protected final function findLocalParameterValue($parameterName)
	{
		$actionRequest = website_BlockController::getInstance()->getRequest();

		$configuration = $this->getConfiguration();
		if ($configuration->hasNonEmptyConfigurationParameter($parameterName))
		{
			return $configuration->getConfigurationParameter($parameterName);
		}

		if ($actionRequest->hasAttribute($parameterName))
		{
			return $actionRequest->getAttribute($parameterName);
		}

		$globalRequest = f_mvc_HTTPRequest::getInstance();
		$session = $globalRequest->getSession();
		if ($session->hasAttribute($parameterName))
		{
			return $session->getAttribute($parameterName);
		}

		if ($actionRequest->hasNonEmptyParameter($parameterName))
		{
			return $actionRequest->getParameter($parameterName);
		}
		return null;
	}

	/**
	 * @return block_BlockConfiguration
	 */
	protected final function getConfiguration()
	{
		$actionRequest = website_BlockController::getInstance()->getRequest();
		return $actionRequest->getAttribute("configuration");
	}
	
	/**
	 * @return f_mvc_HTTPSession
	 */
	protected function getSession()
	{
		return f_mvc_HTTPRequest::getInstance()->getSession();
	}

	/**
	 * @return f_mvc_HTTPRequest
	 */
	protected function getHTTPRequest()
	{
		return f_mvc_HTTPRequest::getInstance();
	}
}