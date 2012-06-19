<?php

class PHPTAL_Php_Attribute_CHANGE_Cache extends ChangeTalAttribute
{
	private static $instance;
	
	/**
	 * @see ChangeTalAttribute::evaluateAll()
	 *
	 * @return boolean
	 */
	protected function evaluateAll()
	{
		return true;
	}
	
		/**
	 * Called before element printing.
	 */
	public function before(PHPTAL_Php_CodeWriter $codewriter)
	{
		parent::before($codewriter);
		$codewriter->pushCode('if (' . $this->getRenderClassName() . '::isNotInCache()) { //');
	}
	
	/**
	 * Called after element printing.
	 */
	public function after(PHPTAL_Php_CodeWriter $codewriter)
	{
		parent::after($codewriter);
		$codewriter->doEcho($this->getRenderClassName() . "::putInCache()");
		$codewriter->pushCode('}');
		$codewriter->doEcho($this->getRenderClassName() . "::endProcessing()");
	}

	/**
	 * @see ChangeTalAttribute::getRenderClassName()
	 *
	 * @return string
	 */
	protected function getRenderClassName()
	{
		return 'website_ChangeCacheRenderer';
	}
	
	/**
	 * @see ChangeTalAttribute::getRenderMethodName()
	 *
	 * @return string
	 */
	protected function getRenderMethodName()
	{
		return 'render';
	}
}

class website_ChangeCacheRenderer
{
	private static $isInCache = false;
	
	private static $cacheDependencies;
	
	/**
	 * @var f_DataCacheItem[]
	 */
	private static $simpleCacheStack = array();
	
	/**
	 *	@return Boolean
	 */
	private function isCacheEnabled()
	{
		return f_DataCacheService::getInstance()->isEnabled() && !DISABLE_BLOCK_CACHE;
	}
	
	/**
	 * @param array $params
	 */
	private static function getCacheDependencies($params)
	{
		$deps = array();
		if (!isset($params['deps']))
		{
			throw new Exception("change:cache must have explicit dependencies");
		}
		
		foreach (explode(",", $params['deps']) as $dependency)
		{
			$deps[] = trim($dependency);
		}
		return $deps;
	}
	
	public static function isNotInCache()
	{
		return !self::$isInCache;
	}
	
	public static function putInCache()
	{	
		if (!self::isCacheEnabled())
		{
			return;
		}
		
		try
		{
			$currentSimpleCache = f_util_ArrayUtils::lastElement(self::$simpleCacheStack);
			$content = ob_get_clean();
			$currentSimpleCache->setValue('html', $content);
			f_DataCacheService::getInstance()->writeToCache($currentSimpleCache);
			echo $currentSimpleCache->getValue('html');
		}
		catch (Exception $e)
		{
			Framework::exception($e);
		}	
	}
	
	public static function endProcessing()
	{
		self::popSimpleCache();
	}
	
	private static function pushSimpleCache($params)
	{
		$action = website_BlockController::getInstance()->getProcessedAction();
		if (!isset($params['name']))
		{
			throw new Exception(__METHOD__ . " change:cache can not build cache with no name");
		}
		
		$cachePath = "changecache_" . get_class($action) . "_" . $params['name'];
		$key = $params;
		$rc = RequestContext::getInstance();
		$key['lang'] = $rc->getLang();
		$key['engine'] = $rc->getUserAgentType() . ':' . $rc->getUserAgentTypeVersion();
		self::$simpleCacheStack[] = f_DataCacheService::getInstance()->readFromCache($cachePath, $params, self::getCacheDependencies($params));
	}
	
	private static function popSimpleCache()
	{
		array_pop(self::$simpleCacheStack);
	}
	
	static function render($params)
	{
		self::$isInCache = false;
		if (!self::isCacheEnabled())
		{
			return;
		}
		ksort($params);
		
		self::pushSimpleCache($params);
		
		$currentSimpleCache = f_util_ArrayUtils::lastElement(self::$simpleCacheStack);
		if (f_DataCacheService::getInstance()->exists($currentSimpleCache))
		{
			self::$isInCache = true;
			echo $currentSimpleCache->getValue('html');
		}
		else
		{
			ob_start();
		}
	}
}