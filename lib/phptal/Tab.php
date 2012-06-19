<?php
//   <a change:tab="name tab1" labeli18n="m.mymodule...tab1-title">bla</a>
/**
 * @package phptal.php.attribute
 */
class PHPTAL_Php_Attribute_CHANGE_Tab extends ChangeTalAttribute
{
	protected function getDefaultValues()
	{
		return array("doTitle" => "true", "titleLevel" => "3", "genTitleClass" => "true");
	}
	
	/**
	 * @return boolean
	 */
	protected function evaluateAll()
	{
		return false;
	}
	
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
		$codewriter->doEchoRaw('PHPTAL_Php_Attribute_CHANGE_Tab::renderEndTag()');
	}

	/**
	 * @param array $params
	 * @return string
	 */
	public static function renderTab($params)
	{
		$currentTabsId = PHPTAL_Php_Attribute_CHANGE_Tabs::getCurrentId();
		$id = $currentTabsId."_".$params["name"];
		if (isset($params["labeli18n"]))
		{
			$label = LocaleService::getInstance()->trans($params["labeli18n"], array('ucf', 'html'));
		}
		else if (isset($params["label"]))
		{
			$label = f_Locale::translate($params["label"]);
		}
		PHPTAL_Php_Attribute_CHANGE_Tabs::addTab($id, $label);
		$html = "<div class=\"tab\" id=\"$id\">";
		
		if ($params["doTitle"])
		{
			$titleLevel = intval($params["titleLevel"]);
			$html .= "<h".$titleLevel;
			if ($params["genTitleClass"])
			{
				$class = PHPTAL_Php_Attribute_CHANGE_H::getClassByLevel($titleLevel);
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
}