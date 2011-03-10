<?php
class website_BlockAction extends f_mvc_Action implements website_PageBlock
{
	const SUBMIT_PARAMETER_NAME = "website_BlockAction_submit";
	const BLOCK_ID_PARAMETER_NAME = "blockId";
	const BLOCK_ERRORS_ATTRIBUTE_KEY = "website_BlockAction_errors";
	const BLOCK_MESSAGES_ATTRIBUTE_KEY = "website_BlockAction_messages";
	const BLOCK_PAGE_ATTRIBUTE = "website_page";
	const BLOCK_BO_MODE_ATTRIBUTE = "uixul_isInBackoffice";
	const BLOCK_PER_PROPERTY_ERRORS_ATTRIBUTE_KEY = "website_BlockAction_propertyErrors";

	/**
	 * @var String
	 */
	private $moduleName;

	/**
	 * @var String
	 */
	private $name;

	public final function __construct()
	{
		$this->moduleName = $this->getModuleNameFromClassName();
		$this->name = $this->getNameFromClassName();
		$this->setLang(RequestContext::getInstance()->getLang());
	}
	
	/**
	 * @return array
	 */
	public function getRequestModuleNames()
	{
		return array($this->getModuleName());
	}
	
	/**
	 * @return Boolean
	 */
	public function isCacheEnabled()
	{
		if ($this->cacheEnabled !== null)
		{
			return $this->cacheEnabled;
		}
		$blockInfo = $this->getBlockInfo();
		if ($blockInfo !== null && $blockInfo->getEditable() && users_BackenduserService::getInstance()->getCurrentBackEndUser() !== null)
		{
			$this->cacheEnabled = false;
			Framework::debug("DISABLE ".$this->getName()." cache because fo editable and logged");
			return false;
		}
		return parent::isCacheEnabled();
	}

	/**
	 * @see f_mvc_Action::execute()
	 *
	 * @param f_mvc_Request $request
	 * @param f_mvc_Response $response
	 * @return String
	 */
	function execute($request, $response)
	{
		// empty
	}

	/**
	 * @return string
	 */
	protected function getConfigurationClassname()
	{
		$className = $this->getModuleName() . '_Block' . ucfirst($this->getName()) . 'Configuration';
		if (f_util_ClassUtils::classExists($className))
		{
			return $className;
		}
		return parent::getConfigurationClassname();
	}

	/**
	 * @return Integer
	 */
	final function getOrder()
	{
		$blockInfo = $this->getBlockInfo();
		if ($blockInfo == null)
		{
			return 0;
		}
		if ("true" == $blockInfo->getAttribute("afterAll"))
		{
			return -100;
		}
		if ("true" == $blockInfo->getAttribute("beforeAll"))
		{
			return 100;
		}
		if ($blockInfo->hasAttribute("order"))
		{
			return intval($blockInfo->getAttribute("order"));
		}
		return 0;
	}
	
	/**
	 * @return block_BlockInfo
	 */
	function getBlockInfo()
	{
		return block_BlockService::getInstance()->getBlockInfo('modules_'. $this->getModuleName().'_'. $this->getName());
	}

	/**
	 * @param String $parameterName
	 * @param String $defaultValue
	 * @return mixed
	 */
	protected function findLocalParameterValue($parameterName, $defaultValue = null)
	{
		if ($this->hasNonEmptyConfigurationParameter($parameterName))
		{
			return $this->getConfigurationParameter($parameterName);
		}

		$actionRequest = website_BlockController::getInstance()->getRequest();
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
		return $defaultValue;
	}

	/**
	 * @param String $paramName
	 * @return Integer | null
	 * @see website_BlockAction::_getDocumentIdParameter()
	 */
	protected function getDocumentIdParameter($paramName = K::COMPONENT_ID_ACCESSOR)
	{
		return $this->_getDocumentIdParameter($paramName, false);
	}

	/**
	 * @param String $paramName
	 * @return Integer | null
	 * @see website_BlockAction::_getDocumentIdParameter()
	 */
	protected function getRequiredDocumentIdParameter($paramName = K::COMPONENT_ID_ACCESSOR)
	{
		return $this->_getDocumentIdParameter($paramName, true);
	}

	/**
	 * @param String $paramName
	 * @param String $className
	 * @return f_persistentdocument_PersistentDocument | null
	 * @see website_BlockAction::_getDocumentParameter()
	 */
	protected function getDocumentParameter($paramName = K::COMPONENT_ID_ACCESSOR, $className = null)
	{
		return $this->_getDocumentParameter($paramName, false, $className);
	}

	/**
	 * @param String $paramName
	 * @param String $expectedClassName the class the document must be an instance of
	 * @return f_persistentdocument_PersistentDocument
	 * @throws Exception if no document could be founded or it is not an instance of the expected
	 * @see website_BlockAction::_getDocumentParameter()
	 */
	protected function getRequiredDocumentParameter($paramName = K::COMPONENT_ID_ACCESSOR, $expectedClassName = null)
	{
		return $this->_getDocumentParameter($paramName, true, $expectedClassName);
	}

	/**
	 * @param String $paramName
	 * @param Boolean $required
	 * @param String $expectedClassName the class the document must be an instance of
	 * @return f_persistentdocument_PersistentDocument
	 * @throws Exception if required and no document could be founded
	 */
	private function _getDocumentParameter($paramName = K::COMPONENT_ID_ACCESSOR, $required, $expectedClassName)
	{
		$id = $this->_getDocumentIdParameter($paramName, $required);
		if ($id !== null)
		{
			$doc = DocumentHelper::getDocumentInstance($id);
			if ($expectedClassName !== null)
			{
				if (!($doc instanceof $expectedClassName))
				{
					throw new Exception("$paramName parameter does not correspond to any $expectedClassName instance but is a ".get_class($doc));
				}
			}
			return $doc;
		}
		return null;
	}
	
	/**
	 * @example 'All' for all content
	 * @return string | null
	 */
	protected function getRefreshSectionName()
	{
		$paramName = $this->getConfiguration()->getBlockId() . '_section';
		return $this->getRequest()->getParameter($paramName);
	}

	/**
	 * @param String $paramName
	 * @param Boolean $required
	 * @return Integer
	 * @throws Exception if required and no document could be founded
	 */
	private function _getDocumentIdParameter($paramName = K::COMPONENT_ID_ACCESSOR, $required)
	{
		$value = $this->findLocalParameterValue($paramName);
		if (is_array($value))
		{
			$value = f_util_ArrayUtils::firstElement($value);
		}
		if (is_numeric($value) && $value > 0)
		{
			return $value;
		}
		if ($required)
		{
			throw new Exception("Could not found any document corresponding to '$paramName' parameter");
		}
		return null;
	}

	/**
	 * Search for $shortViewName locally and if not found, give a try
	 * in website module, searching for 'Generic-Block-'.$shortViewName in it.
	 * @param $shortViewName
	 * @throws TemplateNotFoundException if template could not be found in current module and generic module
	 * @return TemplateObject
	 */
	protected function genericView($shortViewName)
	{
		try
		{
			return $this->getTemplate($shortViewName);
		}
		catch (TemplateNotFoundException $e)
		{
			if (Framework::isDebugEnabled())
			{
				Framework::debug(__METHOD__ . ' EXCEPTION: ' . $e->getMessage());
			}
		}
			$templateName = 'Generic-Block-'.$shortViewName;
			return $this->getTemplateByFullName('modules_website', $templateName);
		}

	/**
	 * @return String[]
	 * @see f_mvc_Action::getInputValidationRules()
	 */
	function getInputValidationRules($request, $bean)
	{
		return array();
	}

	/**
	 * @see f_mvc_Action::getModuleName()
	 *
	 * @return String
	 */
	final function getModuleName()
	{
		return $this->moduleName;
	}

	/**
	 * @return String
	 * @see f_mvc_Action::getName()
	 */
	final function getName()
	{
		return $this->name;
	}

	/**
	 *
	 * @return void
	 * @see f_mvc_Action::onValidateInputFailed()
	 */
	function onValidateInputFailed($request)
	{

	}

	/**
	 * @return boolean
	 * @see f_mvc_Action::validateInput()
	 */
	function validateInput($request, $bean)
	{
		return $this->processValidationRules($this->getInputValidationRules($request, $bean), $request, $bean);
	}

	/**
	 * @see f_mvc_Action::getInputViewName()
	 *
	 * @return String
	 */
	function getInputViewName()
	{
		return website_BlockView::INPUT;
	}

	/**
	 * @see f_mvc_Action::findParameterValue()
	 *
	 * @param unknown_type $parameterName
	 */
	public final function findParameterValue($parameterName)
	{
		if ($this->hasNonEmptyConfigurationParameter($parameterName))
		{
			return $this->getConfigurationParameter($parameterName);
		}

		$actionRequest = website_BlockController::getInstance()->getRequest();
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

		if ($globalRequest->hasNonEmptyParameter($parameterName))
		{
			return $globalRequest->getParameter($parameterName);
		}

		return null;
	}

	/**
	 * @see f_mvc_Action::getCacheKeyParameters()
	 *
	 * @param website_BlockActionRequest $request
	 */
	public function getCacheKeyParameters($request)
	{
		return array_merge($request->getParameters(), $this->getConfigurationParameters());
	}

	/**
	 * @return website_Page
	 */
	public final function getContext()
	{
		return website_BlockController::getInstance()->getContext();
	}

	/**
	 * @see f_mvc_Action::forward()
	 *
	 * @param String $moduleName
	 * @param String $actionName
	 */
	public final function forward($moduleName, $actionName)
	{
		return website_BlockController::getInstance()->forward($moduleName, $actionName);
	}

	/**
	 * @see f_mvc_Action::redirect()
	 *
	 * @param String $moduleName
	 * @param String $actionName
	 * @param Array<String, String> $moduleParams
	 * @param Array<String, String> $absParams
	 */
	public final function redirect($moduleName, $actionName, $moduleParams = null, $absParams = null)
	{
		return website_BlockController::getInstance()->redirect($moduleName, $actionName, $moduleParams, $absParams);
	}

	/**
	 * @see f_mvc_Request::addError()
	 *
	 * @param String $msg
	 */
	public final function addError($msg, $relKey = null)
	{
		$this->addAttributeWithKey($msg, self::BLOCK_ERRORS_ATTRIBUTE_KEY, $relKey);
	}

	/**
	 * @see f_mvc_Request::addMessage()
	 *
	 * @param String $msg
	 */
	public final function addMessage($msg, $relKey = null)
	{
		$this->addAttributeWithKey($msg, self::BLOCK_MESSAGES_ATTRIBUTE_KEY, $relKey);
	}

	/**
	 * @see f_mvc_Request::getErrors()
	 *
	 * @return array<String>
	 */
	public final function getErrors()
	{
		return $this->getAttributeByKey(self::BLOCK_ERRORS_ATTRIBUTE_KEY);
	}

	/**
	 * @see f_mvc_Request::getMessages()
	 *
	 * @return array<String>
	 */
	public final function getMessages()
	{
		return $this->getAttributeByKey(self::BLOCK_MESSAGES_ATTRIBUTE_KEY);
	}

	/**
	 * @see f_mvc_Request::hasErrors()
	 *
	 * @return Boolean
	 */
	public final function hasErrors()
	{
		return $this->hasAttributeForKey(self::BLOCK_ERRORS_ATTRIBUTE_KEY);
	}

	/**
	 * @see f_mvc_Request::hasMessages()
	 *
	 * @return Boolean
	 */
	public final function hasMessages()
	{
		return $this->hasAttributeForKey(self::BLOCK_MESSAGES_ATTRIBUTE_KEY);
	}

	/**
	 * @param String $name
	 * @param array $arguments
	 */
	final function __call($name, $arguments)
	{
		$matches = array();
		if (preg_match('/^validate(.+)Input$/', $name, $matches))
		{
			if (count($arguments) != 2)
			{
				throw new Exception("$name does not have exactly two arguments");
			}
			$executePart = $matches[1];
			$request = $arguments[0];
			return $this->processValidation($executePart, $request, $arguments[1], $request->getParameter("website_FormHelper_relkey"));
		} 
		else
		{
			throw new Exception("Method $name does not exist on " . get_class($this));
		}
	}

	/**
	 * @param website_BlockActionRequest $request
	 * @param Mixed $bean
	 * @param String $executePart
	 * @param String $relKey
	 * @return Boolean
	 */
	protected final function processValidation($executePart, $request, $bean = null, $relKey = null)
	{
		$getRulesMethodName = 'get' . $executePart . 'InputValidationRules';
		if (method_exists($this, $getRulesMethodName))
		{
			return $this->processValidationRules($this->$getRulesMethodName($request, $bean), $request, $bean, $relKey);
		}
		return true;
	}

	/**
	 * @return String
	 */
	public final function getBlockId()
	{
		return $this->getName() . $this->getConfigurationParameter(self::BLOCK_ID_PARAMETER_NAME);
	}

	/**
	 * @return f_mvc_HTTPSession
	 */
	protected function getSession()
	{
		return f_mvc_HTTPRequest::getInstance()->getSession();
	}
	
	/**
	 * Shortcut returning the current BlockActionRequest
	 * @return website_BlockActionRequest
	 */
	protected function getRequest()
	{
		return website_BlockController::getInstance()->getRequest();
	}

	/**
	 * @return f_mvc_HTTPRequest
	 */
	protected function getHTTPRequest()
	{
		return f_mvc_HTTPRequest::getInstance();
	}

	/**
	 * Called when the block is inserted into a page content
	 * @param website_persistentdocument_Page $page
	 * @param Boolean $absolute true if block was introduced considering all versions (langs) of the page. Default value only for compatibility with old interface
	 */
	function onPageInsertion($page, $absolute = false)
	{
		// empty
		if (Framework::isDebugEnabled())
		{
			Framework::debug("Block ".get_class($this)." inserted in page ".$page->getId()." (absolute = " . ($absolute ? 'true' : 'false') . ")");
		}
	}

	/**
	 * Called when the block is removed from a page content
	 * @param website_persistentdocument_Page $page
	 * @param Boolean $absolute true if block was removed considering all versions (langs) of the page. Default value only for compatibility with old interface
	 */
	function onPageRemoval($page, $absolute = false)
	{
		// empty
		if (Framework::isDebugEnabled())
		{
			Framework::debug("Block ".get_class($this)." removed from page ".$page->getId()." (absolute = " . ($absolute ? 'true' : 'false') . ")");
		}
	}

	// protected methods

	/**
	 * @param String $validationRules
	 * @param website_BlockActionRequest $request
	 * @param f_mvc_Bean|null $bean
	 * @param String $relKey
	 * @return Boolean
	 */
	protected final function processValidationRules($validationRules, $request, $bean, $relKey = null)
	{
		if ($bean !== null)
		{
			$bean = BeanUtils::getBean($bean);
		}
		
		$validationResult = true;
		foreach ($validationRules as $validationRuleDeclaration)
		{
			$rule = null;
			$propertyName = null;
			if ($this->isPropertyRule($validationRuleDeclaration, $propertyName, $rule))
			{
				$propertyLabel = $this->getPropertyLabelFromBean($propertyName, $bean);

				if ($bean !== null && BeanUtils::hasProperty($bean, $propertyName))
				{
					$propertyValue = BeanUtils::getProperty($bean, $propertyName);
					$propertyType = BeanUtils::getBeanPropertyInfo($bean, $propertyName)->getType();
				}
				else
				{
					$propertyValue = $request->getParameter($propertyName);
					$propertyType = null;
				}
				$validationProperty = new validation_Property($propertyLabel, $propertyValue, $propertyType);
				$validationErrors = new validation_Errors();

				validation_ValidatorHelper::validate($validationProperty, $rule, $validationErrors);

				if (!$validationErrors->isEmpty())
				{
					$validationResult = false;
					foreach ($validationErrors as $validationError)
					{
						$this->addError($validationError, $relKey);
					}
					$this->addErrorsForProperty($propertyName, $validationErrors, $relKey);
				}
			}
			elseif ($this->isBeanRule($validationRuleDeclaration))
			{
				$errors = null;
				if ($bean !== null)
				{
					$values = $bean;
				}
				else
				{
					$values = $request->getParameters();
				}
				if (!validation_ValidatorHelper::validateBean($validationRuleDeclaration, $values, $errors))
				{
					$validationResult = false;
					foreach ($errors as $propName => $propertyErrors)
					{
						if (is_numeric($propName))
						{
							foreach ($propertyErrors as $propertyError)
							{
								$this->addError($propertyError, $relKey);
							}
						}
						else
						{
							$this->addErrorsForProperty($propName, $propertyErrors, $relKey);
						}
					}
				}
			}
			else
			{
				throw new ValidationException(__CLASS__ . " Invalid validation rule declaration $validationRuleDeclaration");
			}
		}
		return $validationResult;
	}

	/**
	 * @return Boolean
	 */
	protected final function isInBackoffice()
	{
		return $this->getContext()->getAttribute(self::BLOCK_BO_MODE_ATTRIBUTE, false);
	}

	/**
	 * @example $this->getTemplate('Success');
	 * @param String $viewName
	 * @return TemplateObject
	 */
	protected function getTemplate($viewName)
	{
		$templateName = ucfirst($this->moduleName) .'-'. 'Block-' . ucfirst($this->name) . '-' . $viewName;
		return $this->getTemplateByFullName('modules_' . $this->moduleName, $templateName);
	}

	/**
	 * @example $this->getTemplateByFullName('modules_website', 'Website-Block-Taggedmenu-Footer');
	 * @param String $packageName
	 * @param String $templateName
	 * @param String $subDirectory
	 * @return TemplateObject
	 */
	protected function getTemplateByFullName($packageName, $templateName, $subDirectory = null)
	{
		try 
		{
			$directory = 'templates';
			if ($subDirectory !== null)
			{
				$directory .= DIRECTORY_SEPARATOR . $subDirectory;
			}
			$templateLoader = TemplateLoader::getInstance()
			->setMimeContentType(K::HTML)
			->setDirectory($directory)
			->setPackageName($packageName);
	
			return $templateLoader->load($templateName);
		}
		catch (Exception $e)
		{
			return null;	
		}
		
	}

	// private methods


	/**
	 * @param String $propertyName
	 * @param Mixed $bean
	 * @return String
	 */
	private function getPropertyLabelFromBean($propertyName, $bean)
	{
		if ($bean === null || !BeanUtils::hasProperty($bean, $propertyName))
		{
			return LocaleService::getInstance()->transFO("m.".$this->getModuleName().".fo.blocks.".$this->getName().".".$propertyName);
		}

		return f_Locale::translate(BeanUtils::getBeanPropertyInfo($bean, $propertyName)->getLabelKey());
	}

	/**
	 * @param String $validationRuleDeclaration
	 * @param String $propertyName
	 * @param String $rule
	 * @return Boolean
	 */
	private function isPropertyRule($validationRuleDeclaration, &$propertyName, &$rule)
	{
		if (f_util_StringUtils::isEmpty($validationRuleDeclaration))
		{
			return false;
		}
		$ruleLength = strlen($validationRuleDeclaration);
		$firstDelimiterPosition = strpos($validationRuleDeclaration, '{');
		if ($firstDelimiterPosition === false || $validationRuleDeclaration[$ruleLength - 1] != '}')
		{
			return false;
		}
		$propertyName = substr($validationRuleDeclaration, 0, $firstDelimiterPosition);
		$rule = substr($validationRuleDeclaration, $firstDelimiterPosition + 1, $ruleLength - $firstDelimiterPosition - 2);
		return true;
	}

	/**
	 * @param String $validationRuleDeclaration
	 * @param String $propertyName
	 * @param String $rule
	 * @return Boolean
	 */
	private function isBeanRule($validationRuleDeclaration)
	{
		// TODO: more validation ? ... it will throw later anyway ... ?
		if (f_util_StringUtils::isEmpty($validationRuleDeclaration))
		{
			return false;
		}
		return true;
	}

	/**
	 * @return String
	 */
	private function getModuleNameFromClassName()
	{
		$className = get_class($this);
		return substr($className, 0, strpos($className, "_"));
	}

	/**
	 * @return String
	 */
	private function getNameFromClassName()
	{
		$result = array();
		preg_match('/' . $this->getModuleName() . '_Block(\w+)Action/', get_class($this), $result);
		return f_util_StringUtils::lcfirst($result[1]);
	}

	/**
	 * @param String $propertyName
	 * @param String[] $errors
	 */
	protected final function setErrorsForProperty($propertyName, $errors)
	{
		if ($this->hasAttributeForKey(self::BLOCK_PER_PROPERTY_ERRORS_ATTRIBUTE_KEY))
		{
			$errorsPerProperty = $this->getAttributeByKey(self::BLOCK_PER_PROPERTY_ERRORS_ATTRIBUTE_KEY);
		}
		else
		{
			$errorsPerProperty = array();
		}
		$errorsPerProperty[$propertyName] = $errors;
		$this->setAttributeWithKey($errorsPerProperty, self::BLOCK_PER_PROPERTY_ERRORS_ATTRIBUTE_KEY);
	}

	/**
	 * @param String $propertyName
	 * @param String $error
	 * @param String $relKey
	 */
	protected final function addErrorForProperty($propertyName, $error, $relKey = null)
	{
		$key = self::BLOCK_PER_PROPERTY_ERRORS_ATTRIBUTE_KEY;
		if ($this->hasAttributeForKey($key))
		{
			$errorsPerProperty = $this->getAttributeByKey($key);
		}
		else
		{
			$errorsPerProperty = array();
		}
		if (!isset($errorsPerProperty[$propertyName]))
		{
			$errorsPerProperty[$propertyName] = array();
		}
		$errorsPerProperty[$propertyName][] = $error;
		$this->setAttributeWithKey($errorsPerProperty, $key);
		
		if ($relKey === null)
		{
			$relKey = website_BlockController::getInstance()->getRequest()->getParameter("website_FormHelper_relkey");
		}
		if ($relKey !== null)
		{
			$key = self::BLOCK_PER_PROPERTY_ERRORS_ATTRIBUTE_KEY."_relative";
			if ($this->hasAttributeForKey($key))
			{
				$errorsPerProperty = $this->getAttributeByKey($key);
			}
			else
			{
				$errorsPerProperty = array();
			}
			if (!isset($errorsPerProperty[$propertyName]))
			{
				$errorsPerProperty[$propertyName] = array();
			}
			$errorsPerProperty[$propertyName][] = $error;
			$this->setAttributeWithKey($errorsPerProperty, $key, $this->getBlockId());
		}
	}
	
	/**
	 * @param String $propertyName
	 * @param String[] $errors
	 * @param String[] $relKey
	 */
	protected final function addErrorsForProperty($propertyName, $errors, $relKey = null)
	{
		foreach ($errors as $error)
		{
			$this->addErrorForProperty($propertyName, $error, $relKey);
		}	
	}

	/**
	 * @param String $msg
	 * @param String $key
	 */
	private function addAttributeWithKey($msg, $key, $relKey = null)
	{
		$this->addAttributeWithKeyForBlock($msg, $key, $this->getBlockId());
		if ($relKey === null)
		{
			$relKey = website_BlockController::getInstance()->getRequest()->getParameter("website_FormHelper_relkey");
		}
		if ($relKey !== null)
		{
			$this->addAttributeWithKeyForBlock($msg, $key."_relative", $this->getBlockId()."_".$relKey);
		}
	}

	private function addAttributeWithKeyForBlock($msg, $key, $blockId)
	{
		$context = $this->getContext();
		$blockAttributes = $context->getAttribute($key, array());
		if (!isset($blockAttributes[$blockId]))
		{
			$blockAttributes[$blockId] = array();
		}
		$blockAttributes[$blockId][] = $msg;
		$context->setAttribute($key, $blockAttributes);
	}

	private function getAttributeByKey($key)
	{
		$context = $this->getContext();
		$blockAttributes = $context->getAttribute($key, array());
		$blockId = $this->getBlockId();
		if (!isset($blockAttributes[$blockId]))
		{
			return array();
		}
		return $blockAttributes[$blockId];
	}

	private function hasAttributeForKey($key)
	{
		$context = $this->getContext();
		$blockAttributes = $context->getAttribute($key, array());
		$blockId = $this->getBlockId();
		return isset($blockAttributes[$blockId]);
	}

	private function setAttributeWithKey($value, $key, $blockId = null)
	{
		$context = $this->getContext();
		$blockAttributes = $context->getAttribute($key, array());
		if ($blockId === null)
		{
			$blockId = $this->getBlockId();
		}
		$blockAttributes[$blockId] = $value;
		$context->setAttribute($key, $blockAttributes);
	}
	
	// Deprecated
	
	/**
	 * @deprecated (will be removed in 4.0) in favor to getContext()
	 */
	protected final function getPage()
	{
		return $this->getContext();
	}
}