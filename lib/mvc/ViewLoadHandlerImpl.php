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
	protected final function getDocumentParameter($paramName = change_Request::DOCUMENT_ID, $expectedClassName = null)
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
	 * @param string $parameterName
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

		$storage = change_Controller::getInstance()->getStorage();
		if ($storage->read($parameterName))
		{
			return $storage->read($parameterName);
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
	 * @return change_Storage
	 */
	protected function getStorage()
	{
		return change_Controller::getInstance()->getStorage();
	}


	/**
	 * @deprecated (will be removed in RBS Change 5.0) use getStorage instead
	 */
	protected function getSession()
	{
		return f_mvc_HTTPRequest::getInstance()->getSession();
	}
	
	/**
	 * @deprecated (will be removed in RBS Change 5.0)
	 */
	protected function getHTTPRequest()
	{
		return f_mvc_HTTPRequest::getInstance();
	}
	
}