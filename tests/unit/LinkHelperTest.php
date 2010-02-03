<?php
class modules_website_tests_LinkHelperTest extends website_tests_AbstractBaseTest
{

    public function prepareTestCase()
    {
		$this->truncateAllTables();
    	$this->loadSQLResource('sql/C4_intbonjf_generic_tests.sql');

    	RequestContext::clearInstance();
		RequestContext::getInstance('fr en')->setLang('fr');;

		$urs = website_UrlRewritingService::getInstance();
		$urs->removeAllRules();

		$website = DocumentHelper::getDocumentInstance(66);
		TagService::getInstance()->setExclusiveTag($website, 'default_modules_website_default-website');
    }



	public function testGetUrlForDocuments()
	{
		$urs = website_UrlRewritingService::getInstance();

		$page = DocumentHelper::getDocumentInstance(86);

		// Test get URL without any rewriting rules: returns the "raw URL"
		$url = LinkHelper::getUrl($page);
		//echo $url."\n";
		$this->assertEquals(
			'http://generic.intbonjf.rd-change.devlinux.france.rbs.fr/?'.AG_MODULE_ACCESSOR.'=website&amp;'.AG_ACTION_ACCESSOR.'=ViewDetail&amp;websiteParam['.K::COMPONENT_ID_ACCESSOR.']=86',
			$url
			);

			
		// Test get URL without any rewriting rules: returns the "raw URL"
		// + lang information
		$url = LinkHelper::getUrl($page, 'de');
		//echo "URL: $url\n";
		$this->assertEquals(
			'http://generic.intbonjf.rd-change.devlinux.france.rbs.fr/?'.AG_MODULE_ACCESSOR.'=website&amp;'.AG_ACTION_ACCESSOR.'=ViewDetail&amp;websiteParam['.K::COMPONENT_ID_ACCESSOR.']=86&amp;'.K::COMPONENT_LANG_ACCESSOR.'=de',
			$url
			);

		// Add a rule to check if it is taken into consideration by the helper.
		// If the UrlRewriting tests succeeded, this should succeed too, but...
		$urs->addRule(new website_lib_urlrewriting_DocumentModelRule(
			'modules_website', '/$lang/$label,$id', 'modules_website/page', 'detail'
			));
		$url = LinkHelper::getUrl($page);
		//echo "URL: $url\n";
		$this->assertEquals(
			'http://generic.intbonjf.rd-change.devlinux.france.rbs.fr/fr/moyens,86.html',
			$url
			);

		$url = LinkHelper::getUrl($page, 'de');
		//echo "URL: $url\n";
		$this->assertEquals(
			'http://generic.intbonjf.rd-change.devlinux.france.rbs.fr/de/moyens,86.html',
			$url
			);

		$url = LinkHelper::getUrl($page, null, array('block' => 15, 'mode' => 'detail'));
		//echo "URL: $url\n";
		$this->assertEquals(
			'http://generic.intbonjf.rd-change.devlinux.france.rbs.fr/fr/moyens,86.html?block=15&amp;mode=detail',
			$url
			);

		$url = LinkHelper::getUrl($page, 'de', array('block' => 15, 'mode' => 'detail'));
		//echo "URL: $url\n";
		$this->assertEquals(
			'http://generic.intbonjf.rd-change.devlinux.france.rbs.fr/de/moyens,86.html?block=15&amp;mode=detail',
			$url
			);

		TagService::getInstance()->setContextualTag($page, 'contextual_website_website_legal');

		// Add a rule to check if it is taken into consideration by the helper.
		// If the UrlRewriting tests succeeded, this should succeed too, but...
		$urs->addRule(new website_lib_urlrewriting_TaggedPageRule(
			'modules_website', '/mentions-legales', 'contextual_website_website_legal',
			array	(
				'lang' => array('value' => 'fr')
				)
			));
		$url = LinkHelper::getUrl($page);
		//echo "URL: $url\n";
		$this->assertEquals(
			'http://generic.intbonjf.rd-change.devlinux.france.rbs.fr/mentions-legales.html',
			$url
			);

		$url = LinkHelper::getUrl($page, 'de');
		//echo "URL: $url\n";
		$this->assertEquals(
			'http://generic.intbonjf.rd-change.devlinux.france.rbs.fr/de/moyens,86.html',
			$url
			);

		$url = LinkHelper::getUrl($page, null, array('block' => 15, 'mode' => 'detail'));
		//echo "URL: $url\n";
		$this->assertEquals(
			'http://generic.intbonjf.rd-change.devlinux.france.rbs.fr/mentions-legales.html?block=15&amp;mode=detail',
			$url
			);
	}


	public function testGetUrlForModuleAction()
	{
		$urs = website_UrlRewritingService::getInstance();

		$url = LinkHelper::getUrl('news', 'ViewList');
		$this->assertEquals(
			'http://generic.intbonjf.rd-change.devlinux.france.rbs.fr/?'.AG_MODULE_ACCESSOR.'=news&amp;'.AG_ACTION_ACCESSOR.'=ViewList',
			$url
			);

		$urs->addRule(new website_lib_urlrewriting_ModuleActionRule(
			'modules_news', '/news/', 'news', 'ViewList'
			));
		$url = LinkHelper::getUrl('news', 'ViewList');
		$this->assertEquals(
			'http://generic.intbonjf.rd-change.devlinux.france.rbs.fr/news/',
			$url
			);
		$url = LinkHelper::getUrl('news', 'ViewList', array('block' => 15, 'mode' => 'detail'));
		$this->assertEquals(
			'http://generic.intbonjf.rd-change.devlinux.france.rbs.fr/news/?block=15&amp;mode=detail',
			$url
			);
	}

}