<?php
class framework_FunctionCallRecorder
{
	/**
	 * @var Request
	 */
	private $wrappedObject;
	/**
	 * @var String
	 */
	private $wrappedObjectName;
	/**
	 * @var array<String>
	 */
	private $recordedMethodNames;
	/**
	 * @var array<String>
	 */
	private $records;
	

	/**
	 * @param Object $request
	 * @param array<String> $recordedMethodNames
	 * @param String $wrappedObjectName
	 */
	protected function __construct($wrappedObject, &$recordedMethodNames, $wrappedObjectName)
	{
		$this->wrappedObject = $wrappedObject;
		$this->wrappedObjectName = $wrappedObjectName;
		$this->recordedMethodNames = $recordedMethodNames;
		$this->records = array();
		$this->varCount = 0;
	}

	/**
	 * @param string $methodName
	 * @param array $arguments
	 * @return mixed the return of the call of methodName on wrappedObject
	 */
	public function __call($methodName, $arguments)
	{
		if (in_array($methodName, $this->recordedMethodNames))
		{
			$record = '$'.$this->wrappedObjectName.'->'.$methodName.'(';
			$i = 0;
			foreach ($arguments as $arg)
			{
				if ($i > 0) {$record .= ', ';}
				$record .= $this->buildRecord($arg);
				$i++;
			}
			$record .= ');';
			$this->addRecord($record);
		}
		return call_user_func_array(array($this->wrappedObject, $methodName), $arguments);
	}
	
	/**
	 * @param mixed $arg
	 * @return string
	 */
	protected function buildRecord($arg)
	{
		$record = array();
		if ($arg instanceof f_persistentdocument_PersistentDocument)
		{
			$varName = '$obj'.$this->varCount;
			$this->addRecord($varName.' = DocumentHelper::getDocumentInstanceIfExists('.$arg->getId().');');
			$record[] = $varName;
			$this->varCount += 1;
		}
		elseif (is_object($arg))
		{
			$varName = '$obj'.$this->varCount;
			$this->addRecord($varName.' = unserialize('.var_export(serialize($arg), true).');');
			$record[] = $varName;
			$this->varCount += 1;
		}
		elseif (is_array($arg))
		{
			$record[] = "array(";
			foreach ($arg as $key => $value)
			{
				$record[] = var_export($key, true). " => ";
				$record[] = $this->buildRecord($value);
				$record[] = ", ";
			}
			$record[] = ")";
		}
		else
		{
			$record[] = var_export($arg, true);
		}
		return implode('', $record);
	}
	
	/**
	 * @param string $record
	 */
	protected function addRecord($record)
	{
		$this->records[] = $record;
	}

	/**
	 * @return string[]
	 */
	public function getRecords()
	{
		return $this->records;
	}
}

class block_RequestRecorder extends framework_FunctionCallRecorder
{
	private static $recordedMethodNames = array("setParameter", "setParameters", "clearParameters", "removeParameter", "setAttribute", "removeAttribute", "setAttributes");

	/**
	 * @param Request $request
	 */
	public function __construct($request)
	{
		parent::__construct($request, self::$recordedMethodNames, "request");
	}
}

class block_ContextRecorder extends framework_FunctionCallRecorder
{
	private static $recordedMethodNames = array("setAttribute", "setDoctype", "setPlainHeadMarker", "setPlainHeadMarker", "setNavigationtitle",
			"appendToPlainHeadMarker", "setPlainMarker", 
			"addScript", "addLink", "addMeta", "addRssFeed", "addBlockMeta", "addCanonicalParam");

	/**
	 * @param block_BlockContext $context
	 */
	public function __construct($context)
	{
		parent::__construct($context, self::$recordedMethodNames, "blockContext");
	}
}

class block_BlockCache
{
	private $regenerateCache = false;
	
	/**
	 * @var block_BlockHandler
	 */
	private $blockHandler;
	private $cachePath;
	private $isCacheEnabled, $isRequestCacheEnabled;
	/**
	 * @var f_DataCacheItemImpl
	 */
	private $simpleCache;

	/**
	 * @param block_BlockHandler $blockHandler
	 */
	public function __construct($blockHandler)
	{
		$this->blockHandler = $blockHandler;
		$context = $blockHandler->getContext();
		$blockAction = $blockHandler->getBlockAction();
		$this->isCacheEnabled = (self::isCacheEnabled() &&
								 !$context->inBackofficeMode() && !$context->inIndexingMode() &&
								  $blockAction->isCacheEnabled());
		if ($this->isCacheEnabled)
		{
		  	$this->isRequestCacheEnabled = $blockAction->isGlobalRequestCacheEnabled();
		  	$keyParameters = $blockAction->getCacheKeyParameters();
			$keyParameters["https"] = RequestContext::getInstance()->inHTTPS();
		  	$this->simpleCache = f_DataCacheService::getInstance()->readFromCache(get_class($blockAction), $keyParameters, $blockAction->getCacheSpecifications());
		}
	}
	
	private static function isCacheEnabled()
	{
		return f_DataCacheService::getInstance()->isEnabled() && (!defined("AG_DISABLE_BLOCK_CACHE") || !AG_DISABLE_BLOCK_CACHE);
	}

	public function doAction()
	{
		if (!$this->isCacheEnabled)
		{
			$this->blockHandler->doAction();
		}
		else
		{
			$controller = $this->blockHandler->getController();
			$blockContext = $controller->getContext();
			$request = $controller->getGlobalRequest();
			if (!f_DataCacheService::getInstance()->exists($this->simpleCache, 'context') || ($this->isRequestCacheEnabled && !f_DataCacheService::getInstance()->exists($this->simpleCache, 'request')))
			{
				try
				{
					
					$this->regenerateCache = true;
				
					// set recorders
					if ($this->isRequestCacheEnabled)
					{
						$requestRecorder = new block_RequestRecorder($request);
						$controller->setGlobalRequest($requestRecorder);
					}

					$contextRecorder = new block_ContextRecorder($blockContext);
					$controller->setContext($contextRecorder);

					// do action
					$this->blockHandler->doAction();

					// restore state
					if ($this->isRequestCacheEnabled)
					{
						$controller->setGlobalRequest($request);
					}
					$controller->setContext($blockContext);

					if ($this->isRequestCacheEnabled)
					{
						$this->simpleCache->setValue('request', "<?php ".join(null, $requestRecorder->getRecords()));
					}
					$this->simpleCache->setValue('context', "<?php ".join(null, $contextRecorder->getRecords()));
					f_DataCacheService::getInstance()->writeToCache($this->simpleCache);
				}
				catch(Exception $e)
				{
					// "simulate" finally ... I want to Java !
					// restore state
					if ($this->isRequestCacheEnabled)
					{
						$controller->setGlobalRequest($request);
					}
					$controller->setContext($blockContext);
					throw $e;
				}
			}
			else
			{
				if ($this->isRequestCacheEnabled)
				{
					$code = trim($this->simpleCache->getValue('request'), "<?php");
					eval($code);
				}

				$context = trim($this->simpleCache->getValue('context'), "<?php");
				eval($context);
			}
		}
	}

	/**
	 * @return String the content
	 */
	public function doView()
	{
		if ($this->isCacheEnabled && f_DataCacheService::getInstance()->exists($this->simpleCache, 'html') && !$this->regenerateCache)
		{
			return $this->simpleCache->getValue('html');
		}
		else
		{
			$view = $this->blockHandler->getBlockView();
			if (!is_null($view) && $view->isCacheEnabled())
			{
				$simpleViewCache = f_DataCacheService::getInstance()->readFromCache(get_class($view), $view->getCacheKeyParameters(), $view->getCacheSpecifications());
				if (f_DataCacheService::getInstance()->exists($simpleViewCache, 'html'))
				{
					return $simpleViewCache->getValue('html');
				}
				else
				{
					$viewResult = $this->blockHandler->doView();
					$simpleViewCache->setValue('html', $viewResult);
					f_DataCacheService::getInstance()->writeToCache($simpleViewCache);
					return $viewResult;
				}
			}
			else
			{
				$viewResult = $this->blockHandler->doView();
				if ($this->isCacheEnabled)
				{
					$this->simpleCache->setValue('html', $viewResult);
					f_DataCacheService::getInstance()->writeToCache($this->simpleCache);
				}
			}
			return $viewResult;
		}
	}

	/**
	 * @param String $blockName
	 */
	public static function clear($blockName = null)
	{
		f_DataCacheService::getInstance()->clearCacheByNamespace($blockName);
	}
}