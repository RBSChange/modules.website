<?php
// change:actionlink()
//   <a change:actionlink="module 'news'; id news/getId">bla</a>
/**
 * @package phptal.php.attribute
 */
class PHPTAL_Php_Attribute_CHANGE_tabs extends ChangeTalAttribute
{
	private static $called = false;
	private static $id;
	private static $tabs;
	private $parametersString;
	
	public function start()
	{
		$this->tag->headFootDisabled = true;
		$this->parametersString = parent::initParams();
		$this->tag->generator->pushCode('PHPTAL_Php_Attribute_CHANGE_tabs::startTabs('.$this->parametersString . ', $ctx);');
		$this->tag->generator->pushCode('ob_start();');
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
	
	protected function getDefaultValues()
	{
		return array("collapsible" => "false");
	}

	/**
	 * @see ChangeTalAttribute::end()
	 */
	public function end()
	{
		$this->tag->generator->pushCode('$_change_tabsResult_innerContent = ob_get_clean();');
		$this->getRenderMethodCall($this->parametersString);
		$this->tag->generator->doEchoRaw('$_change_tabsResult_innerContent');
		$this->tag->generator->doEchoRaw('PHPTAL_Php_Attribute_CHANGE_tabs::renderEndTag()');
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

	/**
	 * @return Boolean
	 */
	protected function evaluateAll()
	{
		return false;
	}
}