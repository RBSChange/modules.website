<?php
class f_tests_modulewebsite_UrlRewritingServiceTest extends website_tests_AbstractBaseTest
{

	public function prepareTestCase()
	{
		$this->truncateAllTables();
    	$this->loadSQLResource('sql/C4_intbonjf_generic_tests.sql');

    	RequestContext::clearInstance();
		RequestContext::getInstance('fr en')->setLang('fr');;
	}


	public function testGetUrlLabel()
	{
		$urs = test_website_UrlRewritingService::getInstance();
		$labelArray = array(
			'Un beau titre de page avec 3 numéros et des accents' => 'Un-beau-titre-de-page-avec-3-numeros-et-des-accents',
			'àé---îôü...   ?4.' => 'ae-iou.-4'
			);
		foreach ($labelArray as $label => $expectedLabel)
		{
			//echo $label." => ".$urs->getUrlLabel($label)."\n";
			$this->assertEquals($expectedLabel, $urs->getUrlLabel($label));
		}
	}


	// +----------------------------------------------------------------------+
	// |                                                                      |
	// |  addRule, getRule, getRules, ruleExists, removeRule, removeAllRules  |
	// |                                                                      |
	// +----------------------------------------------------------------------+

	public function testRulesManagement()
	{
		$urs = test_website_UrlRewritingService::getInstance();

		// test removeAllRules
		$urs->removeAllRules();
		$this->assertEmpty($urs->getRules());

		// test addRule
		$rule = new website_lib_urlrewriting_DocumentModelRule(
			'modules_test', '/test/$label.$id', 'modules_test/a', 'detail'
			);
		$urs->addRule($rule);
		$this->assertCount(1, $urs->getRules());

		// test ruleExists
		$this->assertTrue($urs->ruleExists($rule));
		$this->assertFalse($urs->ruleExists('modules_othermodule/a detail'));
		$this->assertFalse($urs->ruleExists('modules_test/a otheraction'));

		// test add rule that is already registered
		try
		{
			$rule = new website_lib_urlrewriting_DocumentModelRule(
				'modules_test', '/test2/$label-$id', 'modules_test/a', 'detail'
				);
			$urs->addRule($rule);
			$this->fail('addRule should have thrown a UrlRewritingException');
		}
		catch (UrlRewritingException $e)
		{
			//echo "addRule OK: ".$e->getMessage()."\n";
		}
		$this->assertCount(1, $urs->getRules());

		// test add new rule
		$rule = new website_lib_urlrewriting_DocumentModelRule(
			'modules_test', '/test/', 'modules_test/a', 'list'
			);
		$urs->addRule($rule);
		$this->assertCount(2, $urs->getRules());

		// test removeAllRules
		$urs->removeAllRules();
		$this->assertEmpty($urs->getRules());
	}

	// +----------------------------------------------------------------------+
	// |                                                                      |
	// |  getRuleByUrl & buildRedirectionInfo                                 |
	// |                                                                      |
	// +----------------------------------------------------------------------+

	public function testGetRuleByUrl()
	{
		$urs = test_website_UrlRewritingService::getInstance();
		$suffix = test_website_UrlRewritingService::DEFAULT_URL_SUFFIX;

		// remove all rules eventually loaded in this project
		$urs->removeAllRules();

		// Register a simple rule "/test/label.id":
		// This rule should redirect to module "test", action "ViewDetail"
		// with request parameters "testParam" = array('label' => 'the label', 'id' => the_id).
		$urs->addRule(new website_lib_urlrewriting_DocumentModelRule(
			'modules_test', '/test/$label.$id', 'modules_test/a', 'detail'
			));
		$expected = array(
			'module' => 'test',
			'action' => 'ViewDetail',
			'testParam' => array(
				K::COMPONENT_ID_ACCESSOR => 3,
				'label' => 'dummy'
				)
			);
		// test withOUT suffix
		$url = '/test/dummy.3';
		$rule = $urs->getRuleByUrl($url);
		$this->assertTrue($rule instanceof website_lib_urlrewriting_Rule);
		$infos = $urs->buildRedirectionInfo($rule);
		$this->assertEquals($expected, $infos);


		// test with suffix
		$url = '/test/dummy.3'.$suffix;
		$rule = $urs->getRuleByUrl($url);
		$this->assertTrue($rule instanceof website_lib_urlrewriting_Rule);
		$infos = $urs->buildRedirectionInfo($rule);
		$this->assertEquals($expected, $infos);
	}


	public function testGetRuleByUrl2()
	{
		$urs = test_website_UrlRewritingService::getInstance();

		// remove all rules eventually loaded in this project
		$urs->removeAllRules();

		// Register a simple rule "/test/label-lang.id":
		// This rule should redirect to module "test", action "ViewDetail"
		// with request parameters "testParam" = array('label' => 'the label', 'id' => the_id).
		$urs->addRule(new website_lib_urlrewriting_DocumentModelRule(
			'modules_test', '/$lang/test/$label.$id', 'modules_test/a', 'detail'
			));
		$url = '/fr/test/my-test-document.3.html';
		$rule = $urs->getRuleByUrl($url);
		$this->assertTrue($rule instanceof website_lib_urlrewriting_Rule);
		$infos = $urs->buildRedirectionInfo($rule);
		$this->assertEquals(
			array(
				'module' => 'test',
				'action' => 'ViewDetail',
				'testParam' => array(
					K::COMPONENT_ID_ACCESSOR => 3,
					'label' => 'my-test-document',
					K::COMPONENT_LANG_ACCESSOR => 'fr'
					)
				),
			$infos
			);

		// lang does not match URL template: rule should not be found (and thus should be null)
		// Special note for $lang: the parameter $lang in URL template has, by default, the format [a-z]{2} (website_lib_urlrewriting_Rule::PARAMETER_LANG_DEFAULT_FORMAT)
		$url = '/fra/test/my-test-document.3.html';
		$rule = $urs->getRuleByUrl($url);
		$this->assertTrue( is_null($rule) );

		// no ID: rule should not be found (and thus should be null)
		$url = '/fr/test/my-test-document.html';
		$rule = $urs->getRuleByUrl($url);
		$this->assertTrue( is_null($rule) );
	}


	public function testGetRuleByUrl3()
	{
		$urs = test_website_UrlRewritingService::getInstance();

		$urs->removeAllRules();

		// Register a simple rule "/label,id":
		// This rule should redirect to module "mymodule", action "Show"
		// with request parameters "mymoduleParam" = array('label' => 'the label', 'id' => the_id).
		// Label should contain only letters with a min size of 3 and a max size of 5.
		$urs->addRule(new website_lib_urlrewriting_DocumentModelRule(
			'modules_test', '/$label,$id', 'modules_test/a', 'detail',
			array(
				'label' => array('format' => '[a-z]{3,5}'),
				'module' => array('value' => 'mymodule'),
				'action' => array('value' => 'Show')
				)
			));
		$url = '/foo,1979';
		$expected = array(
			'module' => 'mymodule',
			'action' => 'Show',
			'mymoduleParam' => array(
				K::COMPONENT_ID_ACCESSOR => 1979,
				'label' => 'foo'
				)
			);
		$rule = $urs->getRuleByUrl($url);
		$this->assertTrue($rule instanceof website_lib_urlrewriting_Rule);
		$infos = $urs->buildRedirectionInfo($rule);
		$this->assertEquals($expected, $infos);

		// label does not match URL template: rule should not be found (and thus should be null)
		$url = '/foobar,1979';
		$rule = $urs->getRuleByUrl($url);
		$this->assertTrue( is_null($rule) );

		// ID does not match URL template: rule should not be found (and thus should be null)
		// Special note for $id: the parameter $id in URL template has, by default, the format [0-9]+ (website_lib_urlrewriting_Rule::PARAMETER_ID_DEFAULT_FORMAT)
		$url = '/foo,197a';
		$rule = $urs->getRuleByUrl($url);
		$this->assertTrue( is_null($rule) );
	}

	public function testGetRuleByUrl4()
	{
		$urs = test_website_UrlRewritingService::getInstance();

		$urs->removeAllRules();

		// Register a simple rule "/label,id":
		// This rule should redirect to module "mymodule", action "Show"
		// with request parameters "mymoduleParam" = array('label' => 'the label', 'id' => the_id).
		// Label should contain only letters with a min size of 3 and a max size of 5.
		// ID may be composed of letters and digits.
		$urs->addRule(new website_lib_urlrewriting_DocumentModelRule(
			'modules_test', '/$label,$id', 'modules_test/a', 'test-detail',
			array(
				'label' => array('format' => '[a-z]{3,5}'),
				'module' => array('value' => 'mymodule'),
				'action' => array('value' => 'Show'),
				'id' => array('format' => '[a-z0-9]+')
				)
			));
		$url = '/foo,197a';
		$expected = array(
			'module' => 'mymodule',
			'action' => 'Show',
			'mymoduleParam' => array(
				K::COMPONENT_ID_ACCESSOR => '197a',
				'label' => 'foo'
				)
			);
		$rule = $urs->getRuleByUrl($url);
		$this->assertTrue($rule instanceof website_lib_urlrewriting_Rule);
		$infos = $urs->buildRedirectionInfo($rule);
		$this->assertEquals($expected, $infos);
	}


	// +----------------------------------------------------------------------+
	// |                                                                      |
	// |  getUrl                                                              |
	// |                                                                      |
	// +----------------------------------------------------------------------+

	public function testGetUrl()
	{
		$urs = test_website_UrlRewritingService::getInstance();
		$urs->removeAllRules();

		$urs->addRule(new website_lib_urlrewriting_ModuleActionRule(
			'modules_news',	'/news/', 'news', 'ViewList'
			));
		$urs->addRule(new website_lib_urlrewriting_ModuleActionRule(
			'modules_news',	'/actualite/', 'news', 'ViewList',
			array(
				'lang'   => array('value' => 'fr')
				)
			));
		$url = $urs->getUrl('news', 'ViewList');
		$this->assertEquals('http://generic.intbonjf.rd-change.devlinux.france.rbs.fr/news/', $url);
		//echo "URL: $url\n";

		$url = $urs->getUrl('news', 'ViewList', array('lang' => 'fr'));
		$this->assertEquals('http://generic.intbonjf.rd-change.devlinux.france.rbs.fr/actualite/', $url);
		//echo "URL: $url\n";

		$rule = $urs->getRuleByUrl('/news/');
		$this->assertTrue($rule instanceof website_lib_urlrewriting_Rule);
		$infos = $urs->buildRedirectionInfo($rule);
		$this->assertEquals(
			array('module' => 'news', 'action' => 'ViewList'),
			$infos
			);

		$rule = $urs->getRuleByUrl('/actualite/');
		$this->assertTrue($rule instanceof website_lib_urlrewriting_Rule);
		$infos = $urs->buildRedirectionInfo($rule);
		$this->assertEquals(
			array(
				'module' => 'news',
				'action' => 'ViewList',
				'newsParam' => array(K::COMPONENT_LANG_ACCESSOR => 'fr')
				),
			$infos
			);
	}


	// +----------------------------------------------------------------------+
	// |                                                                      |
	// |  getDocumentUrl                                                      |
	// |                                                                      |
	// +----------------------------------------------------------------------+


    public function testGetDocumentUrl()
    {
		$urs = test_website_UrlRewritingService::getInstance();

		// page
		$rule = new website_lib_urlrewriting_DocumentModelRule(
			'modules_website', '/$label,$id', 'modules_website/page', 'detail'
			);
		$urs->addRule($rule);
		//echo "Rule added: ".$rule->toString()."\n";

		$page = DocumentHelper::getDocumentInstance(121);
		$pageUrl = $urs->getDocumentUrl($page);
		$this->assertEquals('http://generic.intbonjf.rd-change.devlinux.france.rbs.fr/rbs-change-technologies-et-architectures,121'.$urs->getSuffix(), $pageUrl);

		$rule = new website_lib_urlrewriting_DocumentModelRule(
			'modules_website', '/$label,$id/', 'modules_website/topic', 'detail',
			array(
				'id' => array('method' => 'getIndexPageId'),
				)
			);
		$urs->addRule($rule);
		//echo "Rule added: ".$rule->toString()."\n";

		// topic
		$topic = DocumentHelper::getDocumentInstance(118);
		$topicUrl = $urs->getDocumentUrl($topic);
		$this->assertEquals('http://generic.intbonjf.rd-change.devlinux.france.rbs.fr/rbs-change,120/', $topicUrl);

		$ts = TagService::getInstance();

		// tagged page
		$pageMentionsLegales = DocumentHelper::getDocumentInstance(89);
		$ts->addTag($pageMentionsLegales, 'contextual_website_website_legal');
		$urs->addRule(new website_lib_urlrewriting_TaggedPageRule(
			'modules_website', '/mentions-legales', 'contextual_website_website_legal'
			));
		$url = $urs->getDocumentUrl($pageMentionsLegales);
		//echo __LINE__.": URL: $url\n";
		$this->assertEquals('http://generic.intbonjf.rd-change.devlinux.france.rbs.fr/mentions-legales'.$urs->getSuffix(), $url);

		$pageContact = DocumentHelper::getDocumentInstance(143);
		$ts->removeTag($pageContact, 'contextual_website_website_contact');
		$urs->addRule(new website_lib_urlrewriting_TaggedPageRule(
			'modules_website', '/contact', 'contextual_website_website_contact'
			));
		$url = $urs->getDocumentUrl($pageContact);
		//echo __LINE__.": URL: $url\n";
		$this->assertEquals('http://generic.intbonjf.rd-change.devlinux.france.rbs.fr/nos-offres-d-emploi,143'.$urs->getSuffix(), $url);

		$ts->addTag($pageContact, 'contextual_website_website_contact');
		$url = $urs->getDocumentUrl($pageContact);
		//echo __LINE__.": URL: $url\n";
		$this->assertEquals('http://generic.intbonjf.rd-change.devlinux.france.rbs.fr/contact'.$urs->getSuffix(), $url);
	}


	// +----------------------------------------------------------------------+
	// |                                                                      |
	// |  Localized rules                                                     |
	// |                                                                      |
	// +----------------------------------------------------------------------+


    public function testRulesLocalization()
    {
    	$ts = TagService::getInstance();

		$urs = test_website_UrlRewritingService::getInstance();
		$urs->removeAllRules();

		// add three rules with different langs: fr, de and the default (en)
		$urs->addRule(new website_lib_urlrewriting_TaggedPageRule(
			'modules_website', '/mentions-legales', 'contextual_website_website_legal',
			array('lang' => array('value' => 'fr'))
			));
		$urs->addRule(new website_lib_urlrewriting_TaggedPageRule(
			'modules_website', '/legale-erwahnungen', 'contextual_website_website_legal',
			array('lang' => array('value' => 'de'))
			));
		$urs->addRule(new website_lib_urlrewriting_TaggedPageRule(
			'modules_website', '/legal-notice', 'contextual_website_website_legal'
			));

		// tagged page
		$pageMentionsLegales = DocumentHelper::getDocumentInstance(89);
		$ts->addTag($pageMentionsLegales, 'contextual_website_website_legal');

		// fr
		//echo "----\nfr\n";
		RequestContext::clearInstance();
		$requestContext = RequestContext::getInstance('fr en de');
		$requestContext->setLang('fr');
		//echo "LANG = ".$requestContext->getLang()."\n";
		$url = $urs->getDocumentUrl($pageMentionsLegales);
		//echo "URL: $url\n";
		$this->assertEquals('http://generic.intbonjf.rd-change.devlinux.france.rbs.fr/mentions-legales'.$urs->getSuffix(), $url);

		// en
		//echo "----\nen\n";
		RequestContext::clearInstance();
		$requestContext = RequestContext::getInstance('fr en de');
		$requestContext->setLang('en');
		//echo "LANG = ".$requestContext->getLang()."\n";
		$url = $urs->getDocumentUrl($pageMentionsLegales);
		//echo "URL: $url\n";
		$this->assertEquals('http://generic.intbonjf.rd-change.devlinux.france.rbs.fr/legal-notice'.$urs->getSuffix().'?lang=en', $url);

		// de
		//echo "----\nde\n";
		RequestContext::clearInstance();
		$requestContext = RequestContext::getInstance('fr en de');
		$requestContext->setLang('de');
		//echo "LANG = ".$requestContext->getLang()."\n";
		$url = $urs->getDocumentUrl($pageMentionsLegales);
		//echo "URL: $url\n";
		$this->assertEquals('http://generic.intbonjf.rd-change.devlinux.france.rbs.fr/legale-erwahnungen'.$urs->getSuffix(), $url);

		// es
		//echo "----\nes\n";
		RequestContext::clearInstance();
		$requestContext = RequestContext::getInstance('fr en de es');
		$requestContext->setLang('es');
		//echo "LANG = ".$requestContext->getLang()."\n";
		$url = $urs->getDocumentUrl($pageMentionsLegales);
		//echo "URL: $url\n";
		$this->assertEquals('http://generic.intbonjf.rd-change.devlinux.france.rbs.fr/legal-notice'.$urs->getSuffix().'?lang=es', $url);

		$rule = $urs->getRuleByUrl('/legal-notice'.$urs->getSuffix());
		$this->assertTrue($rule instanceof website_lib_urlrewriting_Rule);
		$infos = $urs->buildRedirectionInfo($rule);
		$this->assertEquals(
			array(
				'module' => 'website',
				'action' => 'Display',
				'websiteParam' => array(K::COMPONENT_ID_ACCESSOR => 89)
				),
			$infos
			);

		$rule = $urs->getRuleByUrl('/legale-erwahnungen'.$urs->getSuffix());
		$this->assertTrue($rule instanceof website_lib_urlrewriting_Rule);
		$infos = $urs->buildRedirectionInfo($rule);
		$this->assertEquals(
			array(
				'module' => 'website',
				'action' => 'Display',
				'websiteParam' => array(K::COMPONENT_LANG_ACCESSOR => 'de', K::COMPONENT_ID_ACCESSOR => 89)
				),
			$infos
			);

		$rule = $urs->getRuleByUrl('/mentions-legales'.$urs->getSuffix());
		$this->assertTrue($rule instanceof website_lib_urlrewriting_Rule);
		$infos = $urs->buildRedirectionInfo($rule);
		$this->assertEquals(
			array(
				'module' => 'website',
				'action' => 'Display',
				'websiteParam' => array(K::COMPONENT_LANG_ACCESSOR => 'fr', K::COMPONENT_ID_ACCESSOR => 89)
				),
			$infos
			);
    }


	// +----------------------------------------------------------------------+
	// |                                                                      |
	// |  Suffixes                                                            |
	// |                                                                      |
	// +----------------------------------------------------------------------+


    public function testOtherSuffixes()
    {
		$urs = test_website_UrlRewritingService::getInstance();
		$urs->removeAllRules();
		$lang = RequestContext::getInstance()->getLang();

		RequestContext::clearInstance();
		$requestContext = RequestContext::getInstance('fr en de');
		$requestContext->setLang('fr');

		// page (suffix is too short: default suffix will be appended)
		$rule = new website_lib_urlrewriting_DocumentModelRule(
			'modules_website', '/$label,$id.p', 'modules_website/page', 'detail'
			);
		$urs->addRule($rule);

		$page = DocumentHelper::getDocumentInstance(86);
		$pageUrl = $urs->getDocumentUrl($page);
		//echo "URL: $pageUrl\n";
		$this->assertEquals('http://generic.intbonjf.rd-change.devlinux.france.rbs.fr/moyens,86.p.html', $pageUrl);
		$urs->removeAllRules();

		// page
		$rule = new website_lib_urlrewriting_DocumentModelRule(
			'modules_website', '/$label,$id.php', 'modules_website/page', 'detail'
			);
		$urs->addRule($rule);

		$page = DocumentHelper::getDocumentInstance(86);
		$pageUrl = $urs->getDocumentUrl($page);
		//echo "URL: $pageUrl\n";
		$this->assertEquals('http://generic.intbonjf.rd-change.devlinux.france.rbs.fr/moyens,86.php', $pageUrl);

		$url = '/moyens,86.php';
		$expected = array(
			'module' => 'website',
			'action' => 'ViewDetail',
			'websiteParam' => array(
				K::COMPONENT_ID_ACCESSOR => 86,
				'label' => 'moyens'
				)
			);
		$rule = $urs->getRuleByUrl($url);
		$this->assertTrue($rule instanceof website_lib_urlrewriting_Rule);
		$infos = $urs->buildRedirectionInfo($rule);
		$this->assertEquals($expected, $infos);
    }

}



// Class to test protected methods of website_UrlRewritingService
class test_website_UrlRewritingService extends website_UrlRewritingService
{
	protected static $m_instance = null;

	/**
	 * Returns the unique instance of the website_UrlRewritingService object. It is created if needed.
	 *
	 * @return website_UrlRewritingService
	 */
	public static function getInstance()
	{
		if ( is_null(self::$m_instance) )
		{
			self::$m_instance = new test_website_UrlRewritingService();
		}
		return self::$m_instance;
	}


	public function buildRedirectionInfo($rule)
	{
		f_util_TypeValidator::check($rule, 'website_lib_urlrewriting_Rule');
		return parent::buildRedirectionInfo($rule);
	}

	public function importRules()
	{
		parent::importRules();
	}
}