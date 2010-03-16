<?php
abstract class website_DetailBlockAction extends website_TaggerBlockAction
{	
	/**
	 * @see website_BlockAction::execute()
	 *
	 * @param f_mvc_Request $request
	 * @param f_mvc_Response $response
	 * @return String
	 */
	function execute($request, $response)
	{
		if (!$request->hasAttribute($this->getName()))
		{
			$document = $this->getDocumentParameter();
			if ($document !== null)
			{
				$request->setAttribute(strtolower($this->getName()), $document);	
			}
		}
		if ($this->isInBackoffice())
		{
			return website_BlockView::BACKOFFICE;
		}
		return website_BlockView::SUCCESS;
	}
	
	/**
	 * Called when the block is inserted into a page content:
	 * hide page From Menus And SiteMap and call website_TaggerBlockAction::onPageInsertion()
	 * @param website_persistentdocument_Page $page
	 * @param Boolean $absolute true if block was introduced considering all versions (langs) of the page
	 * @see lib/blocks/website_TaggerBlockAction#onPageInsertion($page, $absolute)
	 */
	function onPageInsertion($page, $absolute = false)
	{
		if ($this->hidePageFromMenusAndSiteMap())
		{
			if ($page->getNavigationVisibility() != 0)
			{
				$page->setNavigationvisibility(0);
				$page->save();
			}
		}
		parent::onPageInsertion($page, $absolute);
	}
	
	/**
	 * Should the block hide page from menus and sitemap ?
	 * @see onPageInsertion($page, $absolute)
	 * @see getDocumentParameter()
	 * @return Boolean true if the block is not statically associated with a document
	 */
	protected function hidePageFromMenusAndSiteMap()
	{
		return !$this->hasNonEmptyConfigurationParameter(K::COMPONENT_ID_ACCESSOR);
	}
}