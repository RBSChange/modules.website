<?php
abstract class website_TaggerBlockAction extends website_BlockAction
{
	/**
	 * Called when the block is inserted into a page content
	 * @param website_persistentdocument_Page $page
	 * @param Boolean $absolute true if block was introduced considering all versions (langs) of the page
	 */
	public function onPageInsertion($page, $absolute = false)
	{
		if ($absolute && $this->canApplyTag($page))
		{
			TagService::getInstance()->addTag($page, $this->getTag(), false);
		}
	}

	/**
	 * Called when the block is removed from a page content
	 * @param website_persistentdocument_Page $page
	 * @param Boolean $absolute true if block was removed considering all versions (langs) of the page
	 */
	public function onPageRemoval($page, $absolute = false)
	{
		if ($absolute && $this->canApplyTag($page))
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
		if ($page->getPersistentModel()->useCorrection() && $page->getCorrectionofid() !== null)
		{
			return false;
		}
		return !$this->hasConfigurationParameter('cmpref') || f_util_StringUtils::isEmpty($this->getConfigurationParameter('cmpref'));
	}
}