<?php
/**
 * website_patch_0367
 * @package modules.website
 */
class website_patch_0367 extends patch_BasePatch
{
 
	/**
	 * Entry point of the patch execution.
	 */
	public function execute()
	{
		$tasks = task_PlannedtaskService::getInstance()->getBySystemtaskclassname('website_CleanCSSAndJSCacheTask');
		if (count($tasks) == 0)
		{
			$task = task_PlannedtaskService::getInstance()->getNewDocumentInstance();
			$task->setSystemtaskclassname('website_CleanCSSAndJSCacheTask');
			$task->setLabel('website_CleanCSSAndJSCacheTask');
			$task->setMaxduration(5);
			$task->setMinute(-1);
			$task->setHour(-1);
			$task->save(ModuleService::getInstance()->getSystemFolderId('task', 'website'));
		}
	}
}