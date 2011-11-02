<?php
/**
 * @deprecated use website_MenuitemdocumentScriptDocumentElement instead
 */
class website_MenuItemPageScriptDocumentElement extends import_ScriptDocumentElement
{
	/**
	 * @deprecated
	 */
	protected function initPersistentDocument()
	{
		return website_MenuitemdocumentService::getInstance()->getNewDocumentInstance();
	}

	/**
	 * @deprecated
	 */
	protected function getDocumentType()
	{
		return "modules_website/menuitemdocument";
	}

	/**
	 * @deprecated
	 */
	protected function getDocumentProperties ()
	{
		$properties = parent::getDocumentProperties();
		if (isset($properties['pageid']))
		{
			$scriptElement = $this->script->getElementById($properties['pageid']);
			unset($properties['pageid']);

			$document = $scriptElement->getPersistentDocument();
			$properties['document'] = $document;
			$properties['label'] = $document->getLabel();
		}
		elseif (isset($properties['pagetag']))
		{
			$documents = TagService::getInstance()->getDocumentsByTag($properties['pagetag']);
			unset($properties['pagetag']);
			if (count($documents) > 0)
			{
				$properties['document'] = $documents[0];
				$properties['label'] = $documents[0]->getLabel();
			}
		}

		return $properties;
	}
}