<?php
class website_ScriptBbcodeattributeElement extends import_ScriptBaseElement
{
	public function endProcess()
	{
		if (!isset($this->attributes['name']))
		{
			throw new Exception('Attribute name not defined');
		}
		$default = isset($this->attributes['default']) && $this->attributes['default'] === 'true';
		$profile = isset($this->attributes['profile']) ? $this->attributes['profile'] : 'default';
		
		$parser = new website_BBCodeParser();
		$this->script->setAttribute($this->attributes['name'], $parser->convertBBCodeToXml($this->getContent()), $default);
	}
}