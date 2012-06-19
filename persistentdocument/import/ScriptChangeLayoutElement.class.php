<?php
class website_ScriptChangeLayoutElement extends import_ScriptBaseElement
{
	/**
	 * @param DOMDocument $document
	 */
	public function generateLayout($document)
	{
		$newLayout = $document->createElementNS(website_PageService::CHANGE_PAGE_EDITOR_NS, "layout");
		$columnChildren = array();
		$freeWidth = 100;
		$columnWithoutWidthCount = 0;
		foreach ($this->script->getChildren($this) as $child)
		{
			if ($child instanceof website_ScriptChangeColumnElement)
			{
				$columnChildren[] = $child;
				if ($child->hasWidth())
				{
					$freeWidth -= $child->getWidth();
				}
				else 
				{
					$columnWithoutWidthCount++;
				}
			}
		}
		foreach ($columnChildren as $child)
		{
			if ($child->hasWidth())
			{
				$columnWidth = $child->getWidth();
			}
			else
			{
				$columnWidth = intval($freeWidth/$columnWithoutWidthCount);
			}
			$newColumn = $child->generateColumn($document, $columnWidth);
			$newLayout->appendChild($newColumn);
		}
		return $newLayout;
	}
}