<?php
class modules_website_tests_WebsiteHelperTest extends website_tests_AbstractBaseUnitTest
{
	public function preTestWebsiteHelperVisibility()
	{
		f_persistentdocument_PersistentProvider::clearInstance();
	}

	public function testWebsiteHelperVisibility()
	{
		$wsf = website_TestFactory::getInstance();
		$topic = $wsf->getNewTopic($website);
	
		$topic->setNavigationVisibility(WebsiteConstants::VISIBILITY_VISIBLE);
		$this->assertTrue(WebsiteHelper::isVisibleInMenu($topic));
		$this->assertTrue(WebsiteHelper::isVisibleInSitemap($topic));
		$this->assertTrue(WebsiteHelper::isVisible($topic));
		$this->assertFalse(WebsiteHelper::isHidden($topic));

		$topic->setNavigationVisibility(WebsiteConstants::VISIBILITY_HIDDEN_IN_MENU_ONLY);
		$this->assertFalse(WebsiteHelper::isVisibleInMenu($topic));
		$this->assertTrue(WebsiteHelper::isVisibleInSitemap($topic));
		$this->assertFalse(WebsiteHelper::isVisible($topic));
		$this->assertFalse(WebsiteHelper::isHidden($topic));
		
		$topic->setNavigationVisibility(WebsiteConstants::VISIBILITY_HIDDEN_IN_SITEMAP_ONLY);
		$this->assertTrue(WebsiteHelper::isVisibleInMenu($topic));
		$this->assertFalse(WebsiteHelper::isVisibleInSitemap($topic));
		$this->assertTrue(WebsiteHelper::isVisible($topic));
		$this->assertFalse(WebsiteHelper::isHidden($topic));		

		$topic->setNavigationVisibility(WebsiteConstants::VISIBILITY_HIDDEN);
		$this->assertFalse(WebsiteHelper::isVisibleInMenu($topic));
		$this->assertFalse(WebsiteHelper::isVisibleInSitemap($topic));
		$this->assertFalse(WebsiteHelper::isVisible($topic));
		$this->assertTrue(WebsiteHelper::isHidden($topic));

		$entry = new website_MenuItem();
		$entry->setNavigationVisibility(WebsiteConstants::VISIBILITY_VISIBLE);
		$this->assertTrue(WebsiteHelper::isVisibleInMenu($entry));
		$this->assertTrue(WebsiteHelper::isVisibleInSitemap($entry));
		$this->assertTrue(WebsiteHelper::isVisible($entry));
		$this->assertFalse(WebsiteHelper::isHidden($entry));

		$entry->setNavigationVisibility(WebsiteConstants::VISIBILITY_HIDDEN_IN_MENU_ONLY);
		$this->assertFalse(WebsiteHelper::isVisibleInMenu($entry));
		$this->assertTrue(WebsiteHelper::isVisibleInSitemap($entry));
		$this->assertFalse(WebsiteHelper::isVisible($entry));
		$this->assertFalse(WebsiteHelper::isHidden($entry));

		$entry->setNavigationVisibility(WebsiteConstants::VISIBILITY_HIDDEN_IN_SITEMAP_ONLY);
		$this->assertTrue(WebsiteHelper::isVisibleInMenu($entry));
		$this->assertFalse(WebsiteHelper::isVisibleInSitemap($entry));
		$this->assertTrue(WebsiteHelper::isVisible($entry));
		$this->assertFalse(WebsiteHelper::isHidden($entry));		
		
		$entry->setNavigationVisibility(WebsiteConstants::VISIBILITY_HIDDEN);
		$this->assertFalse(WebsiteHelper::isVisibleInMenu($entry));
		$this->assertFalse(WebsiteHelper::isVisibleInSitemap($entry));
		$this->assertFalse(WebsiteHelper::isVisible($entry));
		$this->assertTrue(WebsiteHelper::isHidden($entry));

		$page = website_PageService::getInstance()->getNewDocumentInstance();
		try
		{
			WebsiteHelper::isVisibleInMenu($page);
		}
		catch (IllegalArgumentException $e)
		{ }

		try
		{
			WebsiteHelper::isVisibleInMenu(3);
			$this->fail('WebsiteHelper should have thrown an IllegalArgumentException');
		}
		catch (IllegalArgumentException $e)
		{ }
	}
	
}
