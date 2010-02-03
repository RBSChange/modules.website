<?php
class website_patch_0300 extends patch_BasePatch
{
 
	/**
	 * Entry point of the patch execution.
	 */
	public function execute()
	{
		foreach (website_WebsiteService::getInstance()->getAll() as $website)
		{
			$websiteId = $website->getId();
			foreach (website_TopicService::getInstance()->createQuery()->add(Restrictions::descendentOf($websiteId))->find() as $topic)
			{
				$topic->setMeta("websiteId", $websiteId);
				$topic->saveMeta();
			}
			foreach (website_PageService::getInstance()->createQuery()->add(Restrictions::descendentOf($websiteId))->find() as $page)
			{
				$page->setMeta("websiteId", $websiteId);
				$page->saveMeta();
			}
		}
	}

	/**
	 * Returns the name of the module the patch belongs to.
	 *
	 * @return String
	 */
	protected final function getModuleName()
	{
		return 'website';
	}

	/**
	 * Returns the number of the current patch.
	 * @return String
	 */
	protected final function getNumber()
	{
		return '0300';
	}

}