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
		
		$this->execChangeCommand('compile-htaccess');
	}
}