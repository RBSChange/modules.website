<?php
/**
 * website_patch_0356
 * @package modules.website
 */
class website_patch_0356 extends patch_BasePatch
{
	/**
	 * Entry point of the patch execution.
	 */
	public function execute()
	{
		$this->execChangeCommand('update-autoload', array('modules/website'));
		$this->execChangeCommand('compile-locales', array('website'));
		$this->execChangeCommand('website.compile-bbcodes');
	}
}