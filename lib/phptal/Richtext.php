<?php
/**
 * @author inthause
 */
class PHPTAL_Php_Attribute_CHANGE_Richtext extends PHPTAL_Php_Attribute
{
	/**
	 * Called before element printing.
	 */
	public function before(PHPTAL_Php_CodeWriter $codewriter)
	{
		$this->expression = $this->extractEchoType($this->expression);
		$expressions = $codewriter->splitExpression($this->expression);
		$text = "''";
		$configset = "'ChangeDefault'";
		$name = "'richtext'";
		
		foreach ($expressions as $exp)
		{
			list ($attribute, $value) = $this->parseSetExpression($exp);
			switch ($attribute)
			{
				case 'value' :
					$text = $codewriter->evaluateExpression($value);
					break;
				case 'configset' :
					$configset = $codewriter->evaluateExpression($value);
					break;
				case 'name' :
					$name = $codewriter->evaluateExpression($value);
					break;
			}
		}
		$code = 'PHPTAL_Php_Attribute_CHANGE_Richtext::buildEditor('. $name .', '. $configset .', '. $text .')';
		$codewriter->doEchoRaw($code);
	}
	
	public function end()
	{
	}
	
	public static function buildEditor($name, $configset, $text)
	{
		$editor = new FCKeditor($name);
		$editor->ToolbarSet = 'Change';
		$editor->Config['CustomConfigurationsPath'] = LinkHelper::getActionUrl('website', 'RichtextConfig', array('configset' => $configset));
		$editor->Value = $text;
		return $editor->CreateHtml();		
	}
}