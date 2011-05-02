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
		$this->executeModuleScript('markers.xml');	
		
		// Fix #721: import the new workflow first, so it will be activated by
		// default, instead of the old one.
		$this->executeModuleScript('workflow2.xml');
		//$this->executeModuleScript('workflow1.xml');		
		$this->executeModuleScript('iframe.xml');	
	}
}
