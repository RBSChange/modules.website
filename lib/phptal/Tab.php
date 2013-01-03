<?php
/**
 * @deprecated
 */
class PHPTAL_Php_Attribute_CHANGE_tab extends ChangeTalAttribute
{
	/**
	 * @deprecated
	 */
	public function start()
	{
		$this->tag->headFootDisabled = true;
		parent::start();
	}
	
	/**
	 * @deprecated
	 */
	protected function getDefaultValues()
	{
		return array("doTitle" => "true", "titleLevel" => "3", "genTitleClass" => "true");
	}

	/**
	 * @deprecated
	 */
	public function end()
	{
		$this->tag->generator->doEchoRaw('PHPTAL_Php_Attribute_CHANGE_tab::renderEndTag()');
	}

	/**
	 * @deprecated
	 */
	public static function renderTab($params)
	{
		$currentTabsId = PHPTAL_Php_Attribute_CHANGE_tabs::getCurrentId();
		$id = $currentTabsId."_".$params["name"];
		if (isset($params["labeli18n"]))
		{
			$label = LocaleService::getInstance()->transFO($params["labeli18n"], array('ucf', 'html'));
		}
		else if (isset($params["label"]))
		{
			$label = f_Locale::translate($params["label"]);
		}
		PHPTAL_Php_Attribute_CHANGE_tabs::addTab($id, $label);
		$html = "<div class=\"tab\" id=\"$id\">";
		
		if ($params["doTitle"])
		{
			$titleLevel = intval($params["titleLevel"]);
			$html .= "<h".$titleLevel;
			if ($params["genTitleClass"])
			{
				$class = PHPTAL_Php_Attribute_CHANGE_h::getClassByLevel($titleLevel);
				if (f_util_StringUtils::isNotEmpty($class))
				{
					$html .= " class=\"$class\"";
				}
			}
			else if ($params["titleClass"])
			{
				$html .= " class=\"".$params["titleClass"]."\"";
			}
			$html .= ">$label</h$titleLevel>";	
		}
		return $html;
	}
	
	/**
	 * @deprecated
	 */
	static function renderEndTag()
	{
		return "</div>";
	}

	/**
	 * @deprecated
	 */
	protected function evaluateAll()
	{
		return false;
	}
}