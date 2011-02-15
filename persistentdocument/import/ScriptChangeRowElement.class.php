<?php
class website_ScriptChangeRowElement extends import_ScriptBaseElement
{
	/**
	 * @param DOMDocument $document
	 */
	public function generateRow($document)
	{
		$newRow = $document->createElementNS(website_PageService::CHANGE_PAGE_EDITOR_NS, "row");
		if (isset($this->attributes['marginBottom']))
		{
			$newRow->setAttribute("marginBottom", $this->attributes['marginBottom']);
		}
		$blockChildren = array();
		$freeWidth = 100;
		$blockWithoutWidthCount = 0;
		foreach ($this->script->getChildren($this) as $child)
		{
			if ($child instanceof website_ScriptChangeBlockElement)
			{
				$blockChildren[] = $child;
				$blockWithoutWidthCount++;
			}
		}
		foreach ($blockChildren as $child)
		{
				$blockWidth = intval($freeWidth/$blockWithoutWidthCount);
				$newBloc = $child->generateBlock($document, $blockWidth);
				$newRow->appendChild($newBloc);
		}
		return $newRow;
	}
}