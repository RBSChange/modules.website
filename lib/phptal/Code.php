<?php
// change:actionlink()
//   <a change:actionlink="module 'news'; id news/getId">bla</a>
/**
 * @package phptal.php.attribute
 */
class PHPTAL_Php_Attribute_CHANGE_Code extends ChangeTalAttribute
{
	private static $called = false;
	private static $id;
	private static $tabs;
	private $parametersString;

	/**
	 * Called before element printing.
	 */
	public function before(PHPTAL_Php_CodeWriter $codewriter)
	{
		$this->phpelement->headFootDisabled = true;
		$this->parametersString = $this->initParams($codewriter);
		$codewriter->pushCode('ob_start()');
	}

	/**
	 * Called after element printing.
	 */
	public function after(PHPTAL_Php_CodeWriter $codewriter)
	{
		$codewriter->doEchoRaw('PHPTAL_Php_Attribute_CHANGE_Code::renderEndTag(ob_get_clean(), '.$this->parametersString.', $ctx)');
	}

	static function renderEndTag($innerContent, $params, $ctx)
	{
		if (isset($params["lang"]))
		{
			$tranlationTable = get_html_translation_table();
			$tranlationTable["$"] = "&#36;";
			$innerContent = trim($innerContent);
			if (f_util_StringUtils::beginsWith($innerContent, "<![CDATA[") && f_util_StringUtils::endsWith($innerContent, "]]>"))
			{
				if (!isset($params["keepCDATA"]))
				{
					$code = trim(substr($innerContent, 9, -3));
				}
				else
				{
					$code = $innerContent;
				}
				// Because PHPTal continue to substitue variables, even if CDATA section...
				$code = str_replace('&#36;{', '${', $code);
			}
			else
			{
				$code = trim(strtr($innerContent, self::getCodeTranslationTable()));
				//$code = str_replace(array("&#36;", "&gt;", "&lt;"), array("$", ">", "<"), trim($innerContent));
			}
			return self::getHighlighter($params)->highlight($code);
		}
		return null;
	}

	// protected methods

	/**
	 * @return boolean
	 */
	protected function evaluateAll()
	{
		return false;
	}

	// private methods

	private static $highlighterClassName;
	private static $codeTranslationTable;
	private static function getCodeTranslationTable()
	{
		if (self::$codeTranslationTable === null)
		{
			$translationTable = get_html_translation_table();
			$translationTable['${'] = "&#36;{";
			self::$codeTranslationTable = array_flip($translationTable);
		}
		return self::$codeTranslationTable;
	}

	private static function getHighlighter($params)
	{
		if (self::$highlighterClassName === null)
		{
			self::$highlighterClassName = Framework::getConfigurationValue("modules/website/highlighter");
		}
		return f_util_ClassUtils::newInstance(self::$highlighterClassName, $params);
	}
}

/**
 * This highlighter handles only php language
 */
class website_MinimalHighlighter
{
	private $params;

	function __construct($params)
	{
		$this->params = $params;
	}

	function highlight($code)
	{
		if (isset($this->params["lang"]) && $this->params["lang"] == "php")
		{
				$code = "<?php\n".$code;
			$highlighted = highlight_string($code, true);
			$highlighted = substr($highlighted, 6, -7); // remove <code> and </code>
			return "<pre class=\"code\">".$highlighted."</pre>";
		}
		else
		{
			$html = "<pre class=\"code\">";
			$html .= htmlspecialchars($code);
			$html .= "</pre>";
			return $html;
		}
	}
}