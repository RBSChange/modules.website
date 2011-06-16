<?php
/**
 * website_patch_0355
 * @package modules.website
 */
class website_patch_0355 extends patch_BasePatch
{
	/**
	 * Entry point of the patch execution.
	 */
	public function execute()
	{
		$this->executeLocalXmlScript('init.xml');
	}
}