<?php
// change:actionlink()
//   <a change:actionlink="module 'news'; id news/getId">bla</a>
/**
 * @package phptal.php.attribute
 */
class PHPTAL_Php_Attribute_CHANGE_Actionlink extends ChangeTalAttribute
{
	private static $preservedAttributes = array("onclick" => true, "class" => true, "title" => true, "rel" => true);
	
	
	
	/**
	 * Called before element printing.
	 */
	public function before(PHPTAL_Php_CodeWriter $codewriter)
	{
		$this->phpelement->headFootDisabled = true;
		parent::before($codewriter);
	}

		/**
	 * Called after element printing.
	 */
	public function after(PHPTAL_Php_CodeWriter $codewriter)
	{
		$codewriter->doEchoRaw($this->getRenderClassName() . '::renderEndTag()');
	}

	/**
	 * @param array $params
	 * @return string
	 */
	public static function renderActionlink($params)
	{
		$parameters = array();

		// Block parameter
		if (!isset($params['block']))
		{
			// assume we targeted the current block
			$controller = website_BlockController::getInstance();
			$currentBlock = $controller->getProcessedAction();
			$block = $currentBlock->getModuleName()."_".$currentBlock->getName();
			
			$page = website_PageService::getInstance()->getCurrentPage();
		}
		else
		{
			$block = $params['block'];
			$page = null;
			unset($params['block']);
		}

		$blockInfo = explode('_', $block);
		$blockModule = strtolower($blockInfo[0]);
		$blockName = $blockInfo[1];
		if (count($blockInfo) != 2 || !ModuleService::getInstance()->moduleExists($blockModule))
		{
			return self::renderError("Bad block attribute format '$block'. Attribute format must respect the <MODULENAME_BLOCKNAME> format");
		}

		// Lang
		if (isset($params['lang']))
		{
			$lang = $params['lang'];
			unset($params['lang']);
		}
		else
		{
			$lang = null;
		}

		if (isset($params['action']))
		{
			$paramName = $blockModule."Param[".website_BlockAction::SUBMIT_PARAMETER_NAME."][".$blockName."][".$params["action"]."]";
			$parameters[$paramName] = "true";
			unset($params['action']);
		}

		// Class
		if (!isset($params['class']))
		{
			$params['class'] = "link";
		}
		
		if (isset($params['anchor']))
		{
			$anchor = $params['anchor'];
			unset($params['anchor']);
		}
		else
		{
			$anchor = null;
		}

		// build parameters and attributes
		unset($params['tagname']);
		$attributes = array();
		foreach ($params as $key => $value)
		{
			if (!isset(self::$preservedAttributes[$key]))
			{
				$parameters[$blockModule."Param[".$key."]"] = $value;
			}
			else
			{
				$attributes[$key] = $value;
			}
		}

		try
		{
			if ($page !== null)
			{
				$url = LinkHelper::getDocumentUrl($page, $lang, $parameters);	
			}
			else
			{
				$url = website_BlockController::getBlockUrl($block, $lang, $parameters);	
			}
			
			if (empty($url))
			{
				$attributes["class"] .= " link-broken";
				$url = "#";
			}
			elseif ($anchor !== null)
			{
				$url .= "#".$anchor;
			}
			$attributes["href"] = $url;
			return '<a'.self::buildAttributes($attributes).'>';
		}
		catch (Exception $e)
		{
			Framework::exception($e);
			return self::renderError($e->getMessage());
		}
		return '';
	}
	
	private static function renderError($msg)
	{
		return "<a href=\"#\" class=\"error\"><strong>change:actionlink</strong>: ".htmlspecialchars($msg)." ";
	}

	static function renderEndTag()
	{
		return "</a>";
	}

	/**
	 * @return boolean
	 */
	protected function evaluateAll()
	{
		return true;
	}
}
