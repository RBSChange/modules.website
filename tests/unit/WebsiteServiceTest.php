<?php
class modules_website_tests_WebsiteServiceTest extends f_tests_AbstractBaseTest
{
	
	/**
	 * @return String
	 */
	protected function getPackageName()
	{
		return 'modules_website';
	}		
	
    public function prepareTestCase()
    {
    	RequestContext::clearInstance();
		RequestContext::getInstance('fr en')->setLang('fr');;
		$this->truncateAllTables();
    }


	public function testWebsiteService()
	{
		$ws = website_WebsiteService::getInstance();
		$website = $ws->getNewDocumentInstance();
		$this->assertType('website_persistentdocument_website', $website);
		$website->setLabel("Test website 1");
		$website->setUrl('http://www.testwebsite1.com');
		$website->save(ModuleService::getInstance()->getRootFolderId('website'));
		$this->assertNotEmpty($website->getId());

		$query = $this->pp->createQuery('modules_website/menufolder');
		$query->add(Restrictions::childOf($website->getId()));
		$menuFolder = $query->findUnique();
		$this->assertFalse(is_null($menuFolder));
		$this->assertEquals($menuFolder, $ws->getMenuFolder($website));

		$website->delete();
		try
		{
			DocumentHelper::getDocumentInstance($menuFolder->getId());
			$this->fail('menufolder should have been removed.');
		}
		catch (Exception $e)
		{ }

		$website2 = $ws->getNewDocumentInstance();
		$website2->setLabel("Test website 2");
		$website2->setUrl('http://www.testwebsite1.com');
		try
		{
			$website2->save(ModuleService::getInstance()->getRootFolderId('website'));
			$this->fail('Cannot have two websites with the same URL.');
		}
		catch (Exception $e)
		{ }

		$website3 = $ws->getNewDocumentInstance();
		$website3->setLabel("Test website 3");
		$website3->setUrl('http://www.testwebsite3.com');
		try
		{
			$website3->save($website->getId());
			$this->fail('Cannot save a website into another website.');
		}
		catch (Exception $e)
		{ }
	}
}