<?php
class modules_website_tests_WebsiteModuleServiceTest extends website_tests_AbstractBaseTest
{
	const WEBSITE_1_ID = 66;
	const WEBSITE_2_ID = 144;
	const HOME_PAGE_ID = 73;

    public function prepareTestCase()
    {
		$this->truncateAllTables();
    	$this->loadSQLResource('sql/C4_intbonjf_generic_tests.sql');
		$this->loadSQLResource('sql/locales.sql');
    	RequestContext::clearInstance();
		RequestContext::getInstance('fr en')->setLang('fr');

		// Breadcrumb and Sitemap rendering depends on URL rewriting rules.
		// Here I only set the basic rules to suit the tests needs.
		$ts = website_WebsiteModuleService::getInstance();
		$urs = $ts->getUrlRewritingService();
		$urs->removeAllRules();

		$rule = new website_lib_urlrewriting_DocumentModelRule(
			// package
			'modules_website',
			// URL template
			'/$label,$id/',
			// Document Model
			'modules_website/topic',
			// View mode
			'detail',
			array	(
				'id' => array('method' => 'getIndexPageId'),
				'lang' => array('value' => 'fr')
				)
			);
		$urs->addRule($rule);
		$rule = new website_lib_urlrewriting_DocumentModelRule(
			// package
			'modules_website',
			// URL template
			'/$label,$id',
			// Document Model
			'modules_website/page',
			// View mode
			'detail',
			array	(
				'lang' => array('value' => 'fr')
				)
			);
		$urs->addRule($rule);
    }



	public function testBreadcrumb()
	{
		//
		// Test with a regular page:
		//

		// Accueil > Produits > RBS Change > RBS Change - Technologies et architectures
		$page = DocumentHelper::getDocumentInstance(121);
		$ws = website_WebsiteModuleService::getInstance();

		$breadcrumb = $ws->getBreadcrumb($page);
		$this->assertTrue($breadcrumb instanceof Breadcrumb);
		$this->assertEquals('Accueil > Produits > RBS Change > RBS Change - Technologies et architectures', $breadcrumb->renderAsText());
		$this->assertCount(4, $breadcrumb);

		foreach ($breadcrumb as $entry)
		{
			$this->assertTrue($entry instanceof website_MenuItem);
		}
		$this->assertEquals(66, $breadcrumb[0]->getId());
		$this->assertEquals(77, $breadcrumb[1]->getId());
		$this->assertEquals(118, $breadcrumb[2]->getId());
		$this->assertEquals(121, $breadcrumb[3]->getId());

		//
		// Test with an index page (index page does not appear in breadcrumb):
		//

		// Accueil > Produits > RBS Change > RBS Change
		$page = DocumentHelper::getDocumentInstance(120);
		$ws = website_WebsiteModuleService::getInstance();

		$breadcrumb = $ws->getBreadcrumb($page);
		$this->assertTrue($breadcrumb instanceof Breadcrumb);
		$this->assertEquals('Accueil > Produits > RBS Change', $breadcrumb->renderAsText());
		$this->assertCount(3, $breadcrumb);

		foreach ($breadcrumb as $entry)
		{
			$this->assertTrue($entry instanceof website_MenuItem);
		}
		$this->assertEquals(66, $breadcrumb[0]->getId());
		$this->assertEquals(77, $breadcrumb[1]->getId());
		$this->assertEquals(118, $breadcrumb[2]->getId());
	}

	public function testSitemap()
	{
		$ts = website_WebsiteModuleService::getInstance();
		$sitemap = $ts->getSitemap();
		$this->assertCount(18, $sitemap);
	}


	public function testGetSetDefaultWebsite()
	{
		$ws = website_WebsiteModuleService::getInstance();
		$website = $ws->getDefaultWebsite();
		$this->assertTrue($website instanceof website_persistentdocument_website);
		$this->assertEquals(self::WEBSITE_1_ID, $website->getId());

		$secondWebsite = DocumentHelper::getDocumentInstance(self::WEBSITE_2_ID);
		$ws->setDefaultWebsite($secondWebsite);

		$website = $ws->getDefaultWebsite();
		$this->assertTrue($website instanceof website_persistentdocument_website);
		$this->assertEquals($secondWebsite->getId(), $website->getId());

		$ws->setDefaultWebsite(DocumentHelper::getDocumentInstance(self::WEBSITE_1_ID));
		$website = $ws->getDefaultWebsite();
		$this->assertEquals(self::WEBSITE_1_ID, $website->getId());
	}


	public function testIndexPage()
	{
		$ws = website_WebsiteModuleService::getInstance();
		$topic = DocumentHelper::getDocumentInstance(118);
		$indexPage = $ws->getIndexPage($topic);
		$this->assertTrue($indexPage instanceof website_persistentdocument_page);
		$this->assertEquals(120, $indexPage->getId());
		$this->assertTrue($indexPage->getIsIndexPage());
		$this->assertFalse($indexPage->getIsHomePage());

		$website = DocumentHelper::getDocumentInstance(self::WEBSITE_1_ID);
		$homePage = $ws->getIndexPage($website);
		$this->assertEquals(self::HOME_PAGE_ID, $homePage->getId());
		$this->assertTrue($homePage->getIsHomePage());
		$this->assertFalse($homePage->getIsIndexPage());
		$page2 = DocumentHelper::getDocumentInstance(121);
		$ws->setHomePage($page2);
		$this->assertTrue($page2->getIsHomePage());
		$this->assertFalse($homePage->getIsHomePage());
		$this->assertEquals($page2->getId(), $ws->getIndexPage($website)->getId());

		$page = DocumentHelper::getDocumentInstance(self::HOME_PAGE_ID);
		$ws->setHomePage($page);
		$this->assertTrue($page->getIsHomePage());
		$this->assertEquals($page->getId(), $ws->getIndexPage($website)->getId());

		$topic = DocumentHelper::getDocumentInstance(118);
		$page = DocumentHelper::getDocumentInstance(133);
		$ws->setIndexPage($page);
		$this->assertTrue($page->getIsIndexPage());
		$this->assertEquals($page->getId(), $ws->getIndexPage($topic)->getId());
	}


	public function testMenu()
	{
		$ws = website_WebsiteModuleService::getInstance();

		// Test first level items
		$menu = $ws->getMenuByTag('menu-main', 0);
		$this->assertTrue($menu instanceof Menu);
		$this->assertCount(5, $menu);
		foreach ($menu as $menuItem)
		{
			$this->assertTrue($menuItem instanceof website_MenuItem);
		}
		$this->assertEquals(74, $menu[0]->getId());
		$this->assertEquals(75, $menu[1]->getId());
		$this->assertEquals(76, $menu[2]->getId());
		$this->assertEquals(77, $menu[3]->getId());
		$this->assertEquals(78, $menu[4]->getId());

		// Test all menu items
		$menu = $ws->getMenuByTag('menu-main');
		$this->assertCount(17, $menu);

		// Test Menu from a document
		$menuDoc = DocumentHelper::getDocumentInstance(84);
		$menu = $ws->getMenu($menuDoc);
		$this->assertCount(3, $menu);
		$this->assertEquals('modules_website/menuitemfunction', $menu[0]->getDocumentModelName());
		$this->assertEquals('modules_website/page', $menu[1]->getDocumentModelName());
		$this->assertEquals('modules_website/menuitemfunction', $menu[2]->getDocumentModelName());
		$this->assertFalse($menu[0]->getPopup());
		$this->assertFalse($menu[2]->getPopup());
		$this->assertTrue($menu[1]->getPopup());
		$this->assertEquals(800, $menu[1]->getPopupWidth());
		$this->assertEquals(500, $menu[1]->getPopupHeight());
	}


	public function testRestrictedMenu()
	{
		$ws = website_WebsiteModuleService::getInstance();
		$website = $ws->getDefaultWebsite();

		$ws->setCurrentPageId(133);
		$menu = $ws->getRestrictedMenuByTopic($website);
		$this->assertCount(12, $menu);

		$ws->setCurrentPageId(81);
		$menu = $ws->getRestrictedMenuByTopic($website);
		$this->assertCount(9, $menu);
	}

/*
	public function testGetWebsiteByUrl()
	{
		$ws = website_WebsiteModuleService::getInstance();
		$website = $ws->getWebsiteByUrl('http://generic.intbonjf.rd-change.devlinux.france.rbs.fr/index.php?id=3&user=5');
		$this->assertTrue($website instanceof website_persistentdocument_website);

		$website = $ws->getWebsiteByUrl('http://www.sitedetest.com2/index.php?id=3&user=5');
		$this->assertTrue( is_null($website) );
	}
*/
	
}
