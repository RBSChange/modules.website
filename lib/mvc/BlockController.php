<?php
class website_BlockController implements f_mvc_Controller
{
	const PAGE_CACHE_PATH = "page";
	const CONTEXT_CACHE_PATH = "context";
	const HTML_CACHE_PATH = "html";

	const INPUT_ACTION_SUFFIX = "";

	const BEAN_DOCUMENT_ID_PARAMETER = "beanId";

	private $currentBlockId;

	/**
	 * @var website_BlockController
	 */
	private static $instance;

	/**
	 * @var website_Page
	 */
	private $blockContext;

	/**
	 * @var website_BlockActionRequest
	 */
	private $actionRequest;
	private $originalPageContext;

	/**
	 * @var website_BlockAction
	 */
	private $action;

	/**
	 * @var f_SimpleCache;
	 */
	private $simpleCache;

	private $shouldRedirect = false;

	/**
	 * @var website_BlockAction[]
	 */
	private $actionStack = array();

	/**
	 * @var website_BlockActionRequest[]
	 */
	private $actionRequestStack = array();

	/**
	 * @var website_BlockActionResponse[]
	 */
	private $responseStack = array();

	/**
	 * @var website_PageContextRecorder[]
	 */
	private $pageContextRecorderStack = array();

	/**
	 * @var website_BlockActionContextRecorder[]
	 */
	private $blockContextRecorderStack = array();
	private $blockContextRecords = array();

	private $simpleCacheStack = array();

	private $masterResponse;

	/**
	 * @var f_mvc_HttpRequest
	 */
	private $currentRequest;
        
        private $useCache = true;

	/**
	 * @return website_BlockController
	 */
	public static function getInstance()
	{
		if (self::$instance === null)
		{
			$instance =  new self();
			$instance->masterResponse = new website_BlockActionResponse();
			self::$instance = $instance;
		}
		return self::$instance;
	}

	/**
	 * @see f_mvc_Controller::forward()
	 *
	 * @param String $moduleName
	 * @param String $actionName
	 */
	function forward($moduleName, $actionName)
	{
		$action = $this->getActionInstanceByModuleAndName($moduleName, $actionName);
		$this->pushAction($action);
		try
		{
			$currentRequest = f_util_ArrayUtils::lastElement($this->actionRequestStack);
			// TODO : take care of website_BlockAction::SUBMIT_PARAMETER_NAME value ! (must reinitialize to the proper value)
			if ($currentRequest === null)
			{
				$parameters = $this->buildActionRequestParameters($action->getRequestModuleNames(), $this->getGlobalRequest());
			}
			else
			{
				$parameters = array_merge($currentRequest->getParameters(), 
					$this->buildActionRequestParameters($action->getRequestModuleNames(), $this->getGlobalRequest()));
			}
			
			$request = new website_BlockActionRequest($parameters, $moduleName, $actionName);
			if ($currentRequest !== null)
			{
				$request->setAttributes($currentRequest->getAttributes());
			}
			$this->pushRequest($request);
			$this->processInternal();

			$this->popAction();
			$this->popRequest();
		}
		catch (Exception $e)
		{
			// simulate finally
			$this->popAction();
			$this->popRequest();
			throw $e;
		}
	}

	/**
	 * @param $blockType
	 * @param $lang
	 * @param $parameters
	 * @return String
	 */
	static function getBlockUrl($blockType, $lang, $parameters)
	{
		// TODO: test block tag type ?
		$tag = "contextual_website_website_modules_".strtolower($blockType);
		return LinkHelper::getTagUrl($tag, $lang, $parameters);
	}

	/**
	 * @see f_mvc_Controller::redirect()
	 *
	 * @param String $moduleName
	 * @param String $actionName
	 * @param array $moduleParams
	 * @param array $actionParams
	 */
	function redirect($moduleName, $actionName, $moduleParams = null, $absoluteParams = null)
	{
		if ($this->action === null)
		{
			throw new Exception("Can not forward to $moduleName, $actionName, block_Controller is not dispatching");
		}

		$actionInfo = explode("#", $actionName);
		if (count($actionInfo) == 2)
		{
			$actionName = $actionInfo[0];
			$anchor = $actionInfo[1];
		}
		else
		{
			$anchor = null;
		}

		$actionInfo = explode(".", $actionName);
		if (count($actionInfo) == 2)
		{
			$actionName = $actionInfo[0];
			$moduleParams[website_BlockAction::SUBMIT_PARAMETER_NAME][$actionName][$actionInfo[1]] = "true";
		}

		if ($absoluteParams !== null && is_array($absoluteParams))
		{
			$parameters = $absoluteParams;
		}
		else
		{
			$parameters = array();
		}

		if ($moduleParams && is_array($moduleParams))
		{
			$parameters[$moduleName . 'Param'] = $moduleParams;
		}

		$url = self::getBlockUrl(strtolower($moduleName."_".$actionName), null, $parameters);
		if (!f_util_StringUtils::isEmpty($url))
		{
			if ($anchor !== null)
			{
				$url .= "#$anchor";
			}
			$this->redirectToUrl($url);
			return;
		}

		// It's up to the action we redirect to handle cache or not
		if ($this->isRecording())
		{
			$this->stopCacheRecorders();
		}

		$this->process($this->getActionInstanceByModuleAndName($moduleName, $actionName), new f_mvc_FakeHttpRequest($parameters));
		$this->shouldRedirect = true;
	}

	/**
	 * @param String $url
	 * @return void
	 */
	function redirectToUrl($url)
	{
		header("Location: $url");
	}

	/**
	 * @param website_BlockAction $action
	 * @param f_mvc_HTTPRequest $request
	 */
	function process($action, $request)
	{
		try
		{
			$moduleName = $action->getModuleName();
			$this->pushAction($action);
			$parameters = $this->buildActionRequestParameters($action->getRequestModuleNames(), $request);
			$this->pushRequest(new website_BlockActionRequest($parameters, $moduleName, $action->getName()));
			$this->processInternal();
			$this->popAction();
			$this->popRequest();
		}
		catch (Exception $e)
		{
			$this->popAction();
			$this->popRequest();
			throw $e;
		}
	}
	
	/**
	 * @param array $requestModuleNames
	 * @param f_mvc_HTTPRequest $request
	 * @return array
	 */
	private function buildActionRequestParameters($requestModuleNames, $request)
	{
		$parameters = array();
		foreach ($requestModuleNames as $reqModuleName) 
		{
			$p = $request->getModuleParameters($reqModuleName);
			if (is_array($p))
			{
				$parameters = array_merge($p, $parameters);
			}
		}
		return $parameters;
	}

	/**
	 * @param String $moduleName
	 * @param String $actionName
	 * @param array $configurationParameters
	 * @param f_mvc_HTTPRequest $request
         * @param Boolean $cache
	 */
	function processByName($moduleName, $actionName, $request, $configurationParameters = null, $cache = false)
	{
                $oldUseCache = $this->useCache;
                try
                {
                    $this->useCache = $cache;
                    $blockAction = $this->getActionInstanceByModuleAndName($moduleName, $actionName);
                    if ($configurationParameters !== null)
                    {
                            foreach ($configurationParameters as $key => $value)
                            {
                                    $blockAction->setConfigurationParameter($key, $value);
                            }
                    }
                    $this->process($blockAction, $request);
                }
                catch (Exception $e)
                {
                    $this->useCache = $oldUseCache;
                    throw $e;
                }
                $this->useCache = $oldUseCache;
	}

	/**
	 * @param website_persistentdocument_page $page
	 */
	function setPage($page)
	{
		$blockContext = new website_Page($page);
		$this->blockContext = $blockContext;
	}


	/**
	 * @see f_mvc_Controller::getResponse()
	 *
	 * @return website_BlockActionResponse
	 */
	function getResponse()
	{
		if (f_util_ArrayUtils::isEmpty($this->responseStack))
		{
			return $this->masterResponse;
		}
		return f_util_ArrayUtils::lastElement($this->responseStack);
	}

	/**
	 * @see f_mvc_Controller::getRequest()
	 *
	 * @return website_BlockActionRequest
	 */
	function getRequest()
	{
		return $this->actionRequest;
	}

	/**
	 * @return website_Page
	 */
	public function getContext()
	{
		if (!$this->isRecording())
		{
			return $this->blockContext;
		}
		return f_util_ArrayUtils::lastElement($this->pageContextRecorderStack);
	}

	/**
	 * @return website_BlockAction
	 */
	public final function getProcessedAction()
	{
		return $this->action;
	}

	/**
	 */
	private function processInternal()
	{
		$requestContext = RequestContext::getInstance();
		$cacheItem = null;
		try
		{
			$requestContext->beginI18nWork($this->action->getLang());

			$this->initializeAction();

			if ($this->isCacheEnabled() && $this->action->isCacheEnabled())
			{
				$keyParameters = $this->action->getCacheKeyParameters($this->actionRequest);
				$keyParameters["https"] = $requestContext->inHTTPS();
				$cacheItem = f_DataCacheService::getInstance()->readFromCache(get_class($this->action), $keyParameters, $this->action->getCacheDependencies());
				
				if ($this->isActionInCache($cacheItem))
				{
					$this->processActionFromCache($cacheItem);
					$this->endProcessing($cacheItem);
					return;
				}
				$this->startCacheRecorders();
			}

			$this->executeAction();

			if (!$this->shouldRedirect)
			{
				$this->endProcessing($cacheItem);
			}
			else
			{
				$this->shouldRedirect = false;
			}
			$requestContext->endI18nWork();
		}
		catch (Exception $e)
		{
			$this->endProcessing($cacheItem);
			$requestContext->endI18nWork($e);
		}
	}

	/**
	 */
	private function getActionExecuteMethodSuffix()
	{
		if ($this->currentBlockId === null)
		{
			return self::INPUT_ACTION_SUFFIX;
		}
		if (!$this->actionRequest->hasParameter(website_BlockAction::SUBMIT_PARAMETER_NAME))
		{
			return self::INPUT_ACTION_SUFFIX;
		}
		$submitArray = $this->actionRequest->getParameter(website_BlockAction::SUBMIT_PARAMETER_NAME);

		if (!is_array($submitArray))
		{
			return self::INPUT_ACTION_SUFFIX;
		}
		if (isset($submitArray[$this->currentBlockId]) && is_array($submitArray[$this->currentBlockId]))
		{

			return ucfirst(key($submitArray[$this->currentBlockId]));
		}
		$actionName = $this->action->getName();
		if (isset($submitArray[$actionName]) && is_array($submitArray[$actionName]))
		{
			return ucfirst(key($submitArray[$actionName]));
		}
		return self::INPUT_ACTION_SUFFIX;
	}

	/**
	 * @param Exception $e
	 */
	private function endProcessing($cacheItem = null, $e = null)
	{
		if ($this->isCacheEnabled() && $this->action->isCacheEnabled() && $this->isRecording())
		{

			if ($e == null)
			{
				$this->putActionInCache($cacheItem);
			}
			else
			{
				$cacheItem->setInvalid();
			}
			$this->stopCacheRecorders();
			//$this->popSimpleCache();
		}

		if ($e !== null)
		{
			throw $e;
		}
	}
	
	private function getCacheItem()
	{
		return f_util_ArrayUtils::firstElement($this->simpleCacheStack);
	}

	/**
	 */
	private function startCacheRecorders()
	{
		$this->pageContextRecorderStack[] = new website_PageContextRecorder($this->blockContext);
	}

	private function isRecording()
	{
		return f_util_ArrayUtils::isNotEmpty($this->pageContextRecorderStack);
	}

	/**
	 */
	private function stopCacheRecorders()
	{
		array_pop($this->pageContextRecorderStack);
	}

	/**
	 * @param website_BlockAction $action
	 */
	private function putActionInCache($cacheItem)
	{
		$cacheItem->setValue(self::HTML_CACHE_PATH, $this->getResponse()->getWriter()->peek());
		$cacheItem->setValue(self::PAGE_CACHE_PATH, "<?php " . implode('', f_util_ArrayUtils::lastElement($this->pageContextRecorderStack)->getRecords()));
		$cacheItem->setValidity(true);
		f_DataCacheService::getInstance()->writeToCache($cacheItem);
	}

	/**
	 *
	 */
	private function initializeAction()
	{
		if (f_util_ClassUtils::methodExists($this->action, 'initialize'))
		{
			$this->action->initialize($this->actionRequest, $this->getResponse());
		}
	}

	/**
	 * @param String $methodSuffix
	 */
	private function executeAction()
	{
		$methodSuffix = $this->getActionExecuteMethodSuffix();
		$viewName = null;
		$className = get_class($this->action);
		$reflectionClass = new ReflectionClass($className);
		$executeMethodName = 'execute' . $methodSuffix;
		$inputViewMethodName = 'get' . $methodSuffix . 'InputViewName';
		$validationMethodName = 'validate' . $methodSuffix . 'Input';
		$getBeanInfoMethodName = 'get'.$methodSuffix.'BeanInfo';
		$response = $this->getResponse();
		if (!$reflectionClass->hasMethod($executeMethodName))
		{
			throw new Exception(__METHOD__ . ": class $className has no execute method named $executeMethodName");
		}

		if (f_util_StringUtils::isNotEmpty($methodSuffix))
		{
			$needTransaction = $reflectionClass->hasMethod(strtolower($methodSuffix[0]).substr($methodSuffix, 1)."NeedTransaction");
		}
		else
		{
			$needTransaction = $reflectionClass->hasMethod("needTransaction");
		}
		// $needTransaction = f_util_ClassUtils::hasMeta("transaction", $reflectionClass->getMethod($executeMethodName));

		if ($needTransaction)
		{
			$tm = f_persistentdocument_TransactionManager::getInstance();
		}

		if (!$reflectionClass->hasMethod($inputViewMethodName))
		{
			$inputViewMethodName = 'getInputViewName';
		}

		$beanName = null;
		try
		{
			if ($needTransaction)
			{
				$tm->beginTransaction();
			}
			$bean = $this->getBean($reflectionClass, $executeMethodName, $getBeanInfoMethodName, $beanName);
			$invalidProperties = $this->actionRequest->getAttribute('invalidProperties');
			if (!$this->action->$validationMethodName($this->actionRequest, ($bean instanceof f_mvc_DynBean) ? $bean->getWrappedObject() : $bean) || f_util_ArrayUtils::isNotEmpty($invalidProperties))
			{
				// Validation failed
				$this->action->onValidateInputFailed($this->actionRequest);
				$relativeNameOrTemplate = $this->action->$inputViewMethodName($this->actionRequest);
				if ($bean !== null)
				{
					$this->actionRequest->setAttribute($beanName, ($bean instanceof f_mvc_DynBean) ? $bean->getWrappedObject() : $bean);
				}
			}
			else
			{
				if ($bean === null)
				{
					$relativeNameOrTemplate = $this->action->$executeMethodName($this->actionRequest, $response);
				}
				else
				{
					if ($bean instanceof f_mvc_DynBean)
					{
						$bean = $bean->getWrappedObject();
					}
					$relativeNameOrTemplate = $this->action->$executeMethodName($this->actionRequest, $response, $bean);
				}

				// Blocks meta retrieval
				$configuration = $this->action->getConfiguration();
				if (f_util_ClassUtils::methodExists($configuration, "getEnablemetas") &&
					$this->action->getConfiguration()->getEnablemetas())
				{
					$getMetaMethodName = null;
					if ($reflectionClass->hasMethod("get".$methodSuffix."Metas"))
					{
						$getMetaMethodName = "get".$methodSuffix."Metas";
					}
					else if ($reflectionClass->hasMethod("getMetas"))
					{
						$getMetaMethodName = "getMetas";
					}
					
					if ($getMetaMethodName !== null)
					{
						$context = $this->getContext();
						$metas = $this->action->$getMetaMethodName();
						$metaPrefix = $this->action->getModuleName()."_".$this->action->getName();
						foreach ($metas as $metaName => $metaValue)
						{
							$context->addBlockMeta($metaPrefix.".".$metaName, $metaValue);
						}
					}
				}
			}
			if ($needTransaction)
			{
				$tm->commit();
			}
		}
		catch (Exception $e)
		{
			if ($needTransaction)
			{
				$tm->rollBack($e);
			}
			if ($bean !== null)
			{
				$this->actionRequest->setAttribute($beanName, $bean);
			}
			$this->actionRequest->setAttribute('exception', $e);
			$this->forward("website", "exception");
			return;
		}

		// Render the view
		if (!$this->shouldRedirect &&
		($relativeNameOrTemplate instanceof TemplateObject || !f_util_StringUtils::isEmpty($relativeNameOrTemplate)))
		{
			$this->actionRequest->setAttribute('configuration', $this->action->getConfiguration());
			$view = new website_BlockView($relativeNameOrTemplate);
			$view->execute($this->actionRequest, $response);
		}
	}

	/**
	 * @param ReflectionMethod $executeMethod
	 * @param String $beanName
	 * @return stdClass|null
	 */
	private function getBean(ReflectionClass $reflectionClass, $executeMethodName, $getBeanInfoMethodName, &$beanName)
	{
		if ($reflectionClass->hasMethod($getBeanInfoMethodName))
		{
			$beanInfo = f_util_ClassUtils::callMethodArgsOn($this->action, $getBeanInfoMethodName, array($this->actionRequest));
			if ($beanInfo !== null)
			{
				$beanReflectionClass = new ReflectionClass($beanInfo["className"]);
				$beanName = $beanInfo["beanName"];
			}
			else
			{
				return null;
			}
		}
		else
		{
			$methodParameters = $reflectionClass->getMethod($executeMethodName)->getParameters();
			if (count($methodParameters) != 3)
			{
				// No bean
				return null;
			}
			$reflectionParameter = $methodParameters[2];
			$beanReflectionClass = $reflectionParameter->getClass();
			$beanName = $reflectionParameter->getName();

			if ($beanReflectionClass === null)
			{
				if ($reflectionClass->hasMethod("getBeanInfo"))
				{
					$beanInfo = f_util_ClassUtils::callMethodArgsOn($this->action, "getBeanInfo", array($this->actionRequest));
					if ($beanInfo !== null)
					{
						$beanReflectionClass = new ReflectionClass($beanInfo["className"]);
						$beanName = $beanInfo["beanName"];
					}
					else
					{
						return null;
					}
				}
				else
				{
					if (Framework::isWarnEnabled())
					{
						Framework::warn("Third ".$executeMethodName."'s parameter defined (".$beanName.") without hinting it");
					}
					return null;
				}
			}
		}

		if ($this->actionRequest->hasNonEmptyParameter(self::BEAN_DOCUMENT_ID_PARAMETER))
		{
			$bean = BeanUtils::getBeanInstance($beanReflectionClass, $this->actionRequest->getParameter(self::BEAN_DOCUMENT_ID_PARAMETER));
		}
		else
		{
			$bean = BeanUtils::getNewBeanInstance($beanReflectionClass);
		}

		$populateBeanMethodName = "populate".ucfirst($beanName)."Bean";
		if ($reflectionClass->hasMethod($populateBeanMethodName))
		{
			$target = ($bean instanceof f_mvc_DynBean) ? $bean->getWrappedObject() : $bean;
			$invalidProperties = f_util_ClassUtils::callMethodArgsOn($this->action, $populateBeanMethodName, array($target, $this->actionRequest));
			if (!is_array($invalidProperties))
			{
				$invalidProperties = array();
			}
		}
		else
		{
			// Get potential bean includes/excludes
			$exclude = null;
			$include = null;
			$this->getBeanIncludesAndExludes($beanName, $include, $exclude);
			$invalidProperties = BeanUtils::populate($bean, $this->actionRequest->getParameters(), $include, $exclude);
		}

		$this->actionRequest->setAttribute('invalidProperties', $invalidProperties);
		foreach ($invalidProperties as $propertyName => $rawValue)
		{
			$array = array('field' => f_Locale::translate(BeanUtils::getBeanProperyInfo($bean, $propertyName)->getLabelKey()), 'value' => $rawValue);
			$this->getProcessedAction()->addError(f_Locale::translate('&framework.validation.validator.InvalidValue;', $array));
		}
			
		$this->executePostPopulateFilters($bean, $this->actionRequest);
		return $bean;
	}

	/**
	 * @param f_mvc_Bean $bean
	 * @param website_BlockActionRequest $request
	 */
	private function executePostPopulateFilters($bean, $request)
	{
		if ($request->hasParameter("WEBSITE_POST_POPULATE_FILTERS"))
		{
			$filters = $request->getParameter("WEBSITE_POST_POPULATE_FILTERS");
			if (!is_array($filters))
			{
				throw new Exception("Invalid post populate filters parameter");
			}
			foreach ($filters as $filterKey => $filterClassName)
			{
				if (!f_util_ClassUtils::classExists($filterClassName))
				{
					throw new Exception("Filter $filterClassName does not exists");
				}
				$filterClass = new ReflectionClass($filterClassName);
				if (!$filterClass->implementsInterface("website_BeanPopulateFilter"))
				{
					throw new Exception("Invalid bean populate filter $filterClassName");
				}
				$filter = $filterClass->newInstance($filterKey);
				$filter->execute($bean, $request);
			}
		}
	}

	/**
	 * @param String $beanName
	 * @param Array $include
	 * @param Array $exclude
	 */
	private function getBeanIncludesAndExludes($beanName, &$include, &$exclude)
	{
		$excludeName = 'get' . ucfirst($beanName) . 'BeanExclude';
		$actionReflectionClass = new ReflectionClass(get_class($this->action));
		if ($actionReflectionClass->hasMethod($excludeName))
		{
			$exclude = $actionReflectionClass->getMethod($excludeName)->invoke($this->action);
		}

		$includeName = 'get' . ucfirst($beanName) . 'BeanInclude';
		if ($actionReflectionClass->hasMethod($includeName))
		{
			$include = $actionReflectionClass->getMethod($includeName)->invoke($this->action);
		}
	}

	/**
	 * @param String $moduleName
	 * @param String $actionName
	 * @return website_BlockAction
	 */
	public function getActionInstanceByModuleAndName($moduleName, $actionName)
	{
		$className = $moduleName . '_Block' . ucfirst($actionName).'Action';
		if (f_util_ClassUtils::classExists($className))
		{
			$instance = new $className();
			if ($instance instanceof website_BlockAction)
			{
				return $instance;
			}
			throw new Exception("$className is not a website_BlockAction");
		}
		throw new ClassNotFoundException("website_BlockAction $className could not be found");
	}

	// private methods

	/**
	 *	@return Boolean
	 */
	private function isCacheEnabled()
	{
		return $this->useCache && f_DataCacheService::getInstance()->isEnabled() &&
		 (!defined("AG_DISABLE_BLOCK_CACHE") || !AG_DISABLE_BLOCK_CACHE) &&
		 !$this->blockContext->getAttribute(website_BlockAction::BLOCK_BO_MODE_ATTRIBUTE, false);
	}

	private function __construct()
	{
		// empty
	}

	/**
	 * @param f_DataCacheItem $cacheItem
	 * @return Boolean
	 */
	private function isActionInCache($cacheItem)
	{
		return $cacheItem->isValid();
	}

	/**
	 * @param f_DataCacheItem $cacheItem
	 */
	private function processActionFromCache($cacheItem)
	{
		$page = $this->getContext();
		$code = trim($cacheItem->getValue(self::PAGE_CACHE_PATH), "<?php");
		$htmlContent = $cacheItem->getValue(self::HTML_CACHE_PATH);
		eval($code);
		$this->getResponse()->getWriter()->write($htmlContent);
	}

	/**
	 * @param website_BlockAction $action
	 */
	private function pushAction($action)
	{
		if (!f_util_ArrayUtils::isEmpty($this->actionStack))
		{
			$this->responseStack[] = new website_BlockActionResponse();
		}
		$this->actionStack[] = $action;
		$this->action = $action;
		$this->currentBlockId = $this->action->getBlockId();
	}

	private function pushRequest($request)
	{
		$this->actionRequestStack[] = $request;
		$this->actionRequest = $request;
	}

	/**
	 * @param unknown_type $response
	 */
	private function pushResponse($response)
	{
		$this->responseStack[] = new website_BlockActionResponse();
	}

	private function pushSimpleCache($cacheItem)
	{
		$this->simpleCacheStack[] = $cacheItem;
	}

	private function popSimpleCache()
	{
		$cacheItem = array_pop($this->simpleCacheStack);
	//	f_DataCacheService::getInstance()->writeToCache($cacheItem);
	}


	private function popAction()
	{
		array_pop($this->actionStack);
		if (f_util_ArrayUtils::isNotEmpty($this->actionStack))
		{
			$subResponse = $this->getResponse()->getWriter()->getContent();
			array_pop($this->responseStack);
			$this->getResponse()->getWriter()->write($subResponse);
			$this->action = f_util_ArrayUtils::lastElement($this->actionStack);
			$this->currentBlockId = $this->action->getBlockId();
		}
		else
		{
			$this->action = null;
		}
	}

	private function popRequest()
	{
		array_pop($this->actionRequestStack);
		if (f_util_ArrayUtils::isNotEmpty($this->actionRequestStack))
		{
			$this->actionRequest = f_util_ArrayUtils::lastElement($this->actionRequestStack);
		}
		else
		{
			$this->actionRequest = null;
		}
	}

	/**
	 * DEPRECATED METHOD FOR OLD BLOCKS
	 */
	private $globalRequest;
	private $globalContext;

	/**
	 * @deprecated
	 * @return Request
	 */
	public function getGlobalRequest()
	{
		if ($this->globalRequest !== null) { return $this->globalRequest;}
		return HttpController::getInstance()->getContext()->getRequest();
	}

	public function setGlobalRequest($globalRequest)
	{
		$this->globalRequest = $globalRequest;
	}

	/**
	 * @deprecated
	 * @return Context
	 */
	public function getGlobalContext()
	{
		if ($this->globalContext !== null) { return $this->globalContext; }
		return HttpController::getInstance()->getContext();
	}

	public function setContext($globalContext)
	{
		$this->globalContext = $globalContext;
	}
}

class website_PageContextRecorder extends framework_FunctionCallRecorder
{
	private static $recordedMethodNames = array("setAttribute", "removeAttribute", "setMetatitle", "addScript", "setKeywords", "setDescription", "setTitle", "appendToDescription", "addStyle", "addKeyword", "addMeta", "addRssFeed", "addLink", "addBlockMeta");

	/**
	 * @param website_Page $page
	 */
	public function __construct($page)
	{
		parent::__construct($page, self::$recordedMethodNames, "page");
	}
}