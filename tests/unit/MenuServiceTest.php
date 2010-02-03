<?php
class modules_website_tests_MenuServiceTest extends website_tests_AbstractBaseTest
{

	public function testMenuService()
	{
		$ws = website_WebsiteService::getInstance();
		$ms = website_MenuService::getInstance();
		
		$website = website_WebsiteModuleService::getInstance()->getDefaultWebsite();
		
		$menu = $ms->getNewDocumentInstance();
		$this->assertType('website_persistentdocument_menu', $menu);
		$menu->setLabel("Menu 1");
		try
		{
			$menu->save(ModuleService::getInstance()->getRootFolderId('website'));
			$this->fail("A menu has been saved outside a menufolder.");
		}
		catch (Exception $e)
		{ }

		$menu->save($ws->getMenuFolder($website));
	}

}