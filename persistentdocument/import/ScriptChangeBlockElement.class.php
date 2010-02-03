<?php
class website_ScriptChangeBlockElement extends import_ScriptBaseElement
{
	/**
	 * @param DOMDocument $document
	 * @param Integer $width
	 */
	public function generateBlock($document, $width = 100)
	{
		$type = $this->attributes['type'];
		$bloc = $document->createElementNS(website_PageService::CHANGE_PAGE_EDITOR_NS, 'block');
		$bloc->setAttribute('type', $type);
		$bloc->setAttribute('relativeFrontofficeWidth', $width);
		$bloc->setAttribute('__class', str_replace('_', '-', $type));
		
		// Get default parameters.
		// This is required for old blocks who do not use de configuration object to get the parameters.
		if ($type != 'richtext')
		{
			list(, $module, $blockName) = explode('_', $type);
			if (!$module || !$blockName)
			{
				Framework::warn(__METHOD__ . ' bad block type: "'.$type.'" (module = "'.$module.'", block = "'.$blockName.'")');
			}
			else 
			{
				$blockInfo = f_util_ClassUtils::callMethod($module . '_Block' . ucfirst($blockName) . 'Info', 'getInstance');
				foreach ($blockInfo->getParametersInfoArray() as $propertyInfo)
				{
					if ($propertyInfo->hasDefaultValue())
					{
						$bloc->setAttribute('__' . $propertyInfo->getName(), $propertyInfo->getDefaultValue());
					}
				}
			}
		}
		
		foreach ($this->attributes as $name => $value)
		{
			if (f_util_StringUtils::beginsWith($name, '__'))
			{
				$data = explode('-', $name);
				if (isset($data[1]))
				{
					if ($data[1] == 'refid')
					{
						$name = $data[0];
						$scriptElement = $this->script->getDocumentElementById($value);
						$value = $scriptElement->getPersistentDocument()->getId();
					}
				}
				
				$bloc->setAttribute($name, $value);
			}
		}
		
		$content = $this->getContent();
		if (!empty($content) && $type = "richtext")
		{
			$content = preg_replace_callback('#\{ref-id:([^\}]+)\}#', array($this, 'getDocumentIdCallback'), $content);
			$content = website_XHTMLCleanerHelper::clean($content);
			
			$richtextContent = $document->createElementNS(website_PageService::CHANGE_PAGE_EDITOR_NS, 'richtextcontent');
			$richtextContent->appendChild($document->createCDATASection($content));
			$bloc->appendChild($richtextContent);
		}
		return $bloc;
	}
	
	/**
	 * @param Array $matches
	 * @return Integer
	 */
	public function getDocumentIdCallback($matches)
	{
		$document = $this->script->getDocumentElementById($matches[1])->getPersistentDocument();
		if ($document->isNew())
		{
			throw new Exception('Reference '.$matches[1].' is not persisted.');
		}
		return $document->getId();
	}
}