<?php
/**
 * website_patch_0354
 * @package modules.website
 */
class website_patch_0354 extends patch_BasePatch
{ 
	/**
	 * Entry point of the patch execution.
	 */
	public function execute()
	{
		$wsurs = website_UrlRewritingService::getInstance();
		$wsurs->buildRules();
		
		$websites = website_WebsiteService::getInstance()->getAll();
		foreach ($websites as $website) 
		{
			if ($website instanceof website_persistentdocument_website) 
			{
				$page = $website->getIndexPage();
				if ($page === null) {continue;}
				foreach ($website->getI18nInfo()->getLangs() as $lang) 
				{
					$this->log('Set rewrite rule / for ' . $lang. ' Home page ' . $page->getVoLabel());
					$wsurs->setCustomPath('/', $page, $website, $lang);
				}
			}
		}
	}
}