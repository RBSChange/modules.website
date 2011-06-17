<?php
/**
 * @date Tue Apr 24 14:51:43 CEST 2007
 * @author INTcoutL, INTbonjF
 */
class website_Setup extends object_InitDataSetup
{
	public function install()
	{
		//Ajout des liens symboliques pour l'utilisation de fckeditor en front office
		$repositoryPath = f_util_FileUtils::buildWebeditPath('libs', 'fckeditor');
		$browserPath = f_util_FileUtils::buildWebeditPath('modules', 'website',  'lib', 'fckeditor', 'browser');
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

		$this->executeModuleScript('init.xml');	
		
		// Fix #721: import the new workflow first, so it will be activated by
		// default, instead of the old one.
		$this->executeModuleScript('workflow2.xml');
		//$this->executeModuleScript('workflow1.xml');		
		$this->executeModuleScript('iframe.xml');	
		$this->tempFunctionToRemoveIn350();
	}
	
	private function tempFunctionToRemoveIn350()
	{
		// See FIX #35085
		$newPageGroup = f_util_FileUtils::buildWebeditPath("modules/website/patch/0321/page-group.png");
		$oldPageGroup = f_util_FileUtils::buildWebeditPath("libs/icons/small/page-group.png");
		try
		{
			f_util_FileUtils::cp($newPageGroup, $oldPageGroup, f_util_FileUtils::OVERRIDE);
		}
		catch (Exception $e)
		{
			$this->logWarning("Could not override libs/icons/small/page-group.png please do it manually using ".$newPageGroup);
		}
		
		$newPageGroupIndex = f_util_FileUtils::buildWebeditPath("modules/website/patch/0321/page-group-index.png");
		$oldPageGroupIndex = f_util_FileUtils::buildWebeditPath("libs/icons/small/page-group-index.png");
		try
		{
			f_util_FileUtils::cp($newPageGroupIndex, $oldPageGroupIndex, f_util_FileUtils::OVERRIDE);
		}
		catch (Exception $e)
		{
			$this->logWarning("Could not create libs/icons/small/page-group-index.png please do it manually using ".$newPageGroupIndex);
		}
		
		$newPageGroupIndex = f_util_FileUtils::buildWebeditPath("modules/website/patch/0321/page-group-index.png");
		$oldPageGroupIndex = f_util_FileUtils::buildWebeditPath("libs/icons/small/page-group-index.png");
		try
		{
			f_util_FileUtils::cp($newPageGroupIndex, $oldPageGroupIndex, f_util_FileUtils::OVERRIDE);	
		}
		catch (Exception $e)
		{
			$this->logWarning("Could not create libs/icons/small/page-group-index.png please do it manually using ".$newPageGroupIndex);
		}
		
		$newPageGroupHome = f_util_FileUtils::buildWebeditPath("modules/website/patch/0321/page-group-home.png");
		$oldPageGroupHome = f_util_FileUtils::buildWebeditPath("libs/icons/small/page-group-home.png");
		try
		{
			f_util_FileUtils::cp($newPageGroupHome, $oldPageGroupHome, f_util_FileUtils::OVERRIDE);
		}
		catch (Exception $e)
		{
			$this->logWarning("Could not create libs/icons/small/page-group-home.png please do it manually using ".$newPageGroupHome);
		}
	}
}
