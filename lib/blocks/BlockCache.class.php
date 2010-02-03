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
	public function __call($methodName, array $arguments)
	{
		if (in_array($methodName, $this->recordedMethodNames))
		{
			$record = '$'.$this->wrappedObjectName.'->'.$methodName.'(';
			$i = 0;
			foreach ($arguments as $arg)
			{
				if ($i > 0)
				{
					$record .= ', ';
				}
				if (is_object($arg))
				{
					$varName = '$obj'.$this->varCount;
					$this->records[] = $varName.' = unserialize('.var_export(serialize($arg), true).');';
					$record .= $varName;
					$this->varCount++;
				}
				elseif (is_array($arg))
				{
					// TODO : refactor
					$record .= "array(";
					foreach ($arg as $key => $value)
					{
						$record .= var_export($key, true). "=>";
						if (is_object($value))
						{
							$varName = '$obj'.$this->varCount;
							$this->records[] = $varName.' = unserialize('.var_export(serialize($value), true).');';
							$record .= $varName;
							$this->varCount++;
						}
						else
						{
							$record .= var_export($value, true);
						}
						$record .= ",";
					}
					$record .= ")";
				}
				else
				{
					$record .= var_export($arg, true);
				}
				$i++;
			}
			$record .= ');';
			$this->records[] = $record;
		}
		return f_util_ClassUtils::callMethodArgsOn($this->wrappedObject, $methodName, $arguments);
	}

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
	private static $recordedMethodNames = array("setAttribute", "setMetatitle", "setKeywords", "setDescription", "setNavigationtitle", "appendToDescription", "addKeyword", "addScript", "addLink");

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
	 * @var f_SimpleCache
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
		  	$this->simpleCache = new f_SimpleCache(get_class($blockAction), $blockAction->getCacheKeyParameters(), $blockAction->getCacheSpecifications());
		}
	}
	
	private static function isCacheEnabled()
	{
		return f_SimpleCache::isEnabled() && (!defined("AG_DISABLE_BLOCK_CACHE") || !AG_DISABLE_BLOCK_CACHE);
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
			if (!$this->simpleCache->exists('context') || ($this->isRequestCacheEnabled && !$this->simpleCache->exists('request')))
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
						$this->simpleCache->writeToCache('request', "<?php ".join(null, $requestRecorder->getRecords()));
					}
					$this->simpleCache->writeToCache('context', "<?php ".join(null, $contextRecorder->getRecords()));
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
					@include($this->getRequestCachePath());
				}

				@include($this->getContextCachePath());
			}
		}
	}

	/**
	 * @return String the content
	 */
	public function doView()
	{
		if ($this->isCacheEnabled && $this->simpleCache->exists('html') && !$this->regenerateCache)
		{
			return $this->simpleCache->readFromCache('html');
		}
		else
		{
			$view = $this->blockHandler->getBlockView();
			if (!is_null($view) && $view->isCacheEnabled())
			{
				$simpleViewCache = new f_SimpleCache(get_class($view), $view->getCacheKeyParameters(), $view->getCacheSpecifications());
				if ($simpleViewCache->exists('html'))
				{
					return $simpleViewCache->readFromCache('html');
				}
				else
				{
					$viewResult = $this->blockHandler->doView();
					$simpleViewCache->writeToCache('html', $viewResult);
					return $viewResult;
				}
			}
			else
			{
				$viewResult = $this->blockHandler->doView();
				if ($this->isCacheEnabled)
				{
					$this->simpleCache->writeToCache('html',  $viewResult);
				}
			}
			return $viewResult;
		}
	}

	private function getHTMLCachePath()
	{
		return $this->simpleCache->getCachePath('html');
	}

	private function getRequestCachePath()
	{
		return $this->simpleCache->getCachePath('request');
	}

	private function getContextCachePath()
	{
		return $this->simpleCache->getCachePath('context');
	}

	/**
	 * @param String $blockName
	 */
	public static function clear($blockName = null)
	{
		f_SimpleCache::clear($blockName);
	}
}