<?php
// change:actionlink()
//   <a change:actionlink="module 'news'; id news/getId">bla</a>
/**
 * @package phptal.php.attribute
 */
class PHPTAL_Php_Attribute_CHANGE_Tabs extends ChangeTalAttribute
{
	private static $called = false;
	private static $id;
	private static $tabs;
	private $parametersString;

	
	/**
	 * @return Boolean
	 */
	protected function evaluateAll()
	{
		return false;
	}
	
	protected function getDefaultValues()
	{
		return array("collapsible" => "false");
	}
	
	/**
     * Called before element printing.
     */
    public function before(PHPTAL_Php_CodeWriter $codewriter)
    {
		$this->phpelement->headFootDisabled = true;
		$this->parametersString = $this->initParams($codewriter);
		$codewriter->pushCode('PHPTAL_Php_Attribute_CHANGE_Tabs::startTabs('.$this->parametersString . ', $ctx);');
		$codewriter->pushCode('ob_start();');
	}

	/**
     * Called after element printing.
     */
    public function after(PHPTAL_Php_CodeWriter $codewriter)
    {
		$codewriter->pushCode('$_change_tabsResult_innerContent = ob_get_clean();');
		$this->getRenderMethodCall($codewriter, $this->parametersString);
		$codewriter->doEchoRaw('$_change_tabsResult_innerContent');
		$codewriter->doEchoRaw('PHPTAL_Php_Attribute_CHANGE_Tabs::renderEndTag()');
	}	
	
	/**
	 * @param array<String, mixed> $params
	 * @param $ctx
	 */
	static function startTabs($params, $ctx)
	{
		self::$tabs = array();
		// TODO: nested tabs
		if (isset($params["id"]))
		{
			self::$id = $params["id"];
		}
	}
	
	/**
	 * @param String $id
	 * @param String $label
	 * @return void
	 */
	static function addTab($id, $label)
	{
		self::$tabs[$id] = $label;
	}
	
	public static function getCurrentId()
	{
		return self::$id;
	}
	


	/**
	 * @param array $params
	 * @return String
	 */
	public static function renderTabs($params)
	{
		$html = "";
		$options = array();
		if ($params["collapsible"])
		{
			$options[] = "collapsible:true";
		}
		
		$options[] = "select: function(e, ui) {
		var url = ui.tab.toString();
		var hashIndex = url.indexOf('#');
		if (hashIndex != -1) {
			var hash = url.substring(hashIndex);
			window.location.hash = hash;
		} return true;
}";
		
		$optionsJson = "{".join(",", $options)."}";
		
		if (!self::$called)
		{
			$pageContext = website_BlockController::getInstance()->getContext()->getPage();
			$theme = Framework::getConfigurationValue("modules/website/jquery-ui-theme", "south-street");
			$pageContext->addStyle("modules.website.jquery-ui.$theme");
			$pageContext->addScript("modules.website.lib.js.jquery-ui-tabs");
			self::$called = true;
		}
		
		$html .= '<script type="text/javascript">jQuery(document).ready(function(){ jQuery("#'.self::$id.'").tabs('.$optionsJson.'); });</script>';
		$html .= "<div class=\"tabs\" id=\"".self::$id."\">";
		$html .= "<ul>";
		foreach (self::$tabs as $id => $label)
		{
			$html .= "<li><a href=\"#".$id."\">".$label."</a></li>";
		}
		$html .= "</ul>";
		return $html;
	}
	
	static function renderEndTag()
	{
		// TODO: nested tabs
		self::$id = null;
		self::$tabs = null;
		return "</div>";
	}
}