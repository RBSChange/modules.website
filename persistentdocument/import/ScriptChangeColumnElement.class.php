<?php
class website_ScriptChangeColumnElement extends import_ScriptBaseElement
{
	/**
	 * @param DOMDocument $document
	 * @param integer $width
	 */
	public function generateColumn($document, $width = 100)
	{
		$newColumn = $document->createElementNS(website_PageService::CHANGE_PAGE_EDITOR_NS, "col");
		if (isset($this->attributes['marginRight']))
		{
			$newColumn->setAttribute("marginRight", $this->attributes['marginRight']);
		}
		$newColumn->setAttribute("widthPercentage", $width);
		foreach ($this->script->getChildren($this) as $child)
		{
			if ($child instanceof website_ScriptChangeRowElement)
			{
				$newRow = $child->generateRow($document);
				$newColumn->appendChild($newRow);
			}
		}
		return $newColumn;
	}
	
	/**
	 * @return boolean
	 */
	public function hasWidth()
	{
		return isset($this->attributes['width']);
	}
	
	/**
	 * @return boolean
	 */
	public function getWidth()
	{
		if ($this->hasWidth())
		{
			return $this->attributes['width'];
		}
		else
		{
			return 0;
		}
	}
}