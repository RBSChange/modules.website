<?php
/**
 * website_MenuitemdocumentScriptDocumentElement
 * @package modules.website.persistentdocument.import
 */
class website_MenuitemdocumentScriptDocumentElement extends import_ScriptDocumentElement
{
	/**
	 * @return website_persistentdocument_menuitemdocument
	 */
	protected function initPersistentDocument()
	{
		$doc = $this->getComputedAttribute('document', true);
		$menu = $this->getParentDocument()->getPersistentDocument();
		$mids = website_MenuitemdocumentService::getInstance();
		$item = $mids->createQuery()->add(Restrictions::eq('document', $doc))->add(Restrictions::eq('menu', $menu))->findUnique();
		if ($item === null)
		{
			$item = website_MenuitemdocumentService::getInstance()->getNewDocumentInstance();
			$item->setDocument($doc);
		}
		return $item;	
	}
}