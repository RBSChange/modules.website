<?php
class PHPTAL_Php_Attribute_CHANGE_loadhandler extends PHPTAL_Php_Attribute
{

	public function start()
	{
		// Block parameter
		if (f_util_StringUtils::isEmpty($this->expression))
		{
			return $this->renderError("you must define loadhandler attribute");
		}

		$handlerClassName = $this->expression;
		$handlerClass = new ReflectionClass($handlerClassName);
		if (!$handlerClass->implementsInterface("website_ViewLoadHandler"))
		{
			return $this->renderError("$handlerClassName is not a website_ViewLoadHandler");
		}
		$code = '/* PHPTAL_Php_Attribute_CHANGE_loadhandler begin */
if (!isset($blockController))
{
	$blockController = website_BlockController::getInstance();
	$request = $blockController->getRequest();
	$response = $blockController->getResponse();
	$fakeRequest = new website_TemplateRequest($request, $tpl);
}
$loadHandler = new '.$handlerClassName.'();';
		if (isset($this->tag->attributes['args']))
		{
			$handlerParams = website_BlockView::parseHandlerArgs($this->tag->attributes['args']);
			$code .= "\n".'$loadHandler->setParameters('.var_export($handlerParams, true).');';
		}
		$code .= '$loadHandler->execute($fakeRequest, $response);
/* PHPTAL_Php_Attribute_CHANGE_loadhandler end */ ';
		$this->tag->generator->pushCode($code);
		return null;
	}

	public function end()
	{
		// empty
	}

	private function renderError($msg)
	{
		return "<strong>change:loadhandler</strong>: ".htmlspecialchars($msg)." ";
	}
}

class website_TemplateRequest
{
	/**
	 * @var website_BlockActionRequest
	 */
	private $request;
	/**
	 * @var PHPTal
	 */
	private $template;

	/**
	 * @param website_BlockActionRequest $request
	 * @param PHPTal $template
	 */
	function __construct($request, $template)
	{
		$this->request = $request;
		$this->template = $template;
	}

	function setAttribute($attrName, $attrValue)
	{
		$this->request->setAttribute($attrName, $attrValue);
		$this->template->set($attrName, $attrValue);
	}

	function hasAttribute($attrName)
	{
		return $this->request->hasAttribute($attrName);
	}

	function getAttribute($attrName)
	{
		return $this->request->getAttribute($attrName);
	}

	function hasParameter($name)
	{
		return $this->request->hasParameter($name);
	}

	function hasNonEmptyParameter($name)
	{
		return $this->request->hasNonEmptyParameter($name);
	}

	function getParameter($name)
	{
		return $this->request->getParameter($name);
	}
	
	function getParameters()
	{
		return $this->request->getParameters();
	}
}