<?php
/**
 * website_patch_0369
 * @package modules.website
 */
class website_patch_0369 extends patch_BasePatch
{
	/**
	 * Entry point of the patch execution.
	 */
	public function execute()
	{
		change_ConfigurationService::getInstance()->addProjectConfigurationEntry('modules/website/useBeanPopulateStrictMode', 'false');
	}
}