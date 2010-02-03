<?php
/**
 * @author inthause
 */
class PHPTAL_Php_Attribute_CHANGE_richtext extends PHPTAL_Php_Attribute
{
	public function start()
	{
		$this->expression = $this->extractEchoType($this->expression);
		$expressions = $this->tag->generator->splitExpression($this->expression);
		$text = '';
		$configset = '\'ChangeDefault\'';
		$name = '\'richtext\'';
		
		foreach ($expressions as $exp)
		{
			list ($attribute, $value) = $this->parseSetExpression($exp);
			switch ($attribute)
			{
				case 'value' :
					$text = $this->evaluate($value);
					break;
				case 'configset' :
					$configset = $this->evaluate($value);
					break;
				case 'name' :
					$name = $this->evaluate($value);
					break;
			}
		}
		$code = 'PHPTAL_Php_Attribute_CHANGE_richtext::buildEditor('. $name .', '. $configset .', '. $text .')';
		$this->doEcho($code);
	}
	
	public function end()
	{
	}
	
	public static function buildEditor($name, $configset, $text)
	{
		$editor = new FCKeditor($name);
		$editor->ToolbarSet = 'Change';
		$editor->Config['CustomConfigurationsPath'] = '/index.php?module=website&action=RichtextConfig&configset=' . $configset;
		$editor->Value = $text;
		return $editor->CreateHtml();		
	}
}