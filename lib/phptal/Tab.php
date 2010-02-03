<?php
// change:actionlink()
//   <a change:actionlink="module 'news'; id news/getId">bla</a>
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
		return array("doTitle" => "true");
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
		$label = f_Locale::translate($params["label"]);
		PHPTAL_Php_Attribute_CHANGE_tabs::addTab($id, $label);
		$html = "<div class=\"tab\" id=\"".$id."\">";
		
		if ($params["doTitle"])
		{
			$html .= "<h3 class=\"heading-three\">".$label."</h3>";	
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