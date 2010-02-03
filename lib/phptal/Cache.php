<?php

class PHPTAL_Php_Attribute_CHANGE_cache extends ChangeTalAttribute
{
	private static $instance;
	
	
	/**
	 * @see ChangeTalAttribute::evaluateAll()
	 *
	 * @return Boolean
	 */
	protected function evaluateAll()
	{
		return true;
	}
	
	/**
	 * @see ChangeTalAttribute::start()
	 *
	 */
	public function start()
	{
		parent::start();
		$this->tag->generator->pushCode('if (' . $this->getRenderClassName() . '::isNotInCache()) { //');
	}
	
	/**
	 * @see ChangeTalAttribute::end()
	 *
	 */
	public function end()
	{
		parent::end();
		$this->tag->generator->doEcho($this->getRenderClassName() . "::putInCache()");
		$this->tag->generator->pushCode('}');
		$this->tag->generator->doEcho($this->getRenderClassName() . "::endProcessing()");
	}
	

	/**
	 * @see ChangeTalAttribute::getRenderClassName()
	 *
	 * @return String
	 */
	protected function getRenderClassName()
	{
		return 'website_ChangeCacheRenderer';
	}
	
	/**
	 * @see ChangeTalAttribute::getRenderMethodName()
	 *
	 * @return String
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
	 * @var f_SimpleCache[]
	 */
	private static $simpleCacheStack = array();
	
	/**
	 *	@return Boolean
	 */
	private function isCacheEnabled()
	{
		return f_SimpleCache::isEnabled() && (!defined("AG_DISABLE_BLOCK_CACHE") || !AG_DISABLE_BLOCK_CACHE);
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
		
		$actionRequest = website_BlockController::getInstance()->getRequest();
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
			$currentSimpleCache->writeToCache('html', $content);
			echo file_get_contents($currentSimpleCache->getCachePath('html'));
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
		self::$simpleCacheStack[] = new f_SimpleCache($cachePath, $params, self::getCacheDependencies($params));
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
		if ($currentSimpleCache->exists('html'))
		{
			self::$isInCache = true;
			echo file_get_contents($currentSimpleCache->getCachePath('html'));
		}
		else
		{
			ob_start();
		}
	}
}