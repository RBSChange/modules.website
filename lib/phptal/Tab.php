<?php
//   <a change:tab="name tab1" labeli18n="m.mymodule...tab1-title">bla</a>
/**
 * @package phptal.php.attribute
 */
class PHPTAL_Php_Attribute_CHANGE_tab extends ChangeTalAttribute
{
	public function start()
	{
		$this->tag->headFootDisabled = true;
		parent::start();
	}
	
	protected function getDefaultValues()
	{
		return array("doTitle" => "true", "titleLevel" => "3", "genTitleClass" => "true");
	}

	/**
	 * @see ChangeTalAttribute::end()
	 */
	public function end()
	{
		$this->tag->generator->doEchoRaw('PHPTAL_Php_Attribute_CHANGE_tab::renderEndTag()');
	}

	/**
	 * @param array $params
	 * @return String
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
	
	static function renderEndTag()
	{
		return "</div>";
	}

	/**
	 * @return Boolean
	 */
	protected function evaluateAll()
	{
		return false;
	}
}