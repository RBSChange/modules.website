<?php
class website_Setup extends object_InitDataSetup
{
	public function install()
	{
		$this->addProjectConfigurationEntry('tal/prefix/alternateclass', 'website_TalesAlternateClass');
		$this->addProjectConfigurationEntry('tal/prefix/url', 'website_TalesUrl');
		$this->addProjectConfigurationEntry('tal/prefix/tagurl', 'website_TalesUrl');
		$this->addProjectConfigurationEntry('tal/prefix/actionurl', 'website_TalesUrl');
		$this->addProjectConfigurationEntry('tal/prefix/currenturl', 'website_TalesUrl');
		
		// Make symbolic links for fckeditor.
		$repositoryPath = f_util_FileUtils::buildWebeditPath('libs', 'fckeditor');
		$browserPath = f_util_FileUtils::buildWebeditPath('modules', 'website', 'lib', 'fckeditor', 'browser');
		$webappFckPath = f_util_FileUtils::buildDocumentRootPath('fckeditor');
		$webappBrowserPath = f_util_FileUtils::buildDocumentRootPath('fckeditorbrowser');
		
		if (is_link($webappFckPath))
		{
			@unlink($webappFckPath);
		}
		$this->addInfo("Creating symbolic link for fckeditor...");
		f_util_FileUtils::symlink($repositoryPath, $webappFckPath);
		
		if (is_link($webappBrowserPath))
		{
			@unlink($webappBrowserPath);
		}
		$this->addInfo("Creating symbolic link for fckeditorbrowser...");
		f_util_FileUtils::symlink($browserPath, $webappBrowserPath);
		
		// Import datas (lists, workflows, etc).
		$this->executeModuleScript('init.xml');
		$this->executeModuleScript('workflow2.xml');
		
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
