<?php
abstract class website_TaggerBlockAction extends website_BlockAction
{
	/**
	 * Called when the block is inserted into a page content
	 * @param website_persistentdocument_Page $page
	 */
	function onPageInsertion($page)
	{
		if ($this->canApplyTag($page))
		{
			TagService::getInstance()->addTag($page, $this->getTag(), false);
		}
	}

	/**
	 * Called when the block is removed from a page content
	 * tag the page if some page is not already tagged with the block's tag
	 * @param website_persistentdocument_Page $page
	 */
	function onPageRemoval($page)
	{
		if ($this->canApplyTag($page))
		{
			TagService::getInstance()->removeTag($page, $this->getTag());
		}
	}

	/**
	 * @return String
	 */
	protected function getTag()
	{
		return "contextual_website_website_modules_".$this->getModuleName()."_".strtolower($this->getName());
	}

	/**
	 * @param website_persistentdocument_Page $page
	 * @return Boolean true if page correction is not activated or if the page is not a correction
	 */
	protected function canApplyTag($page)
	{
		return !$page->getPersistentModel()->useCorrection() || $page->getCorrectionofid() === null;
	}
}