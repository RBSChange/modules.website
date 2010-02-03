<?php
class modules_website_tests_ChangeMenuTagTest extends website_tests_AbstractBaseTest
{
	/**
	 * @var f_persistentdocument_DocumentService
	 */
	private $ds = null;


    public function prepareTestCase()
    {
		$this->truncateAllTables();
    	$this->loadSQLResource('sql/C4_intbonjf_generic_tests.sql');

    	RequestContext::clearInstance();
		RequestContext::getInstance('fr en')->setLang('fr');;

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

		website_WebsiteModuleService::getInstance()->setCurrentPageId(133);
    }


	public function testTalChangeMenuBasic()
	{
		$ws = website_WebsiteModuleService::getInstance();

		$menu = $ws->getMenuByTag('menu-main');
		$template = TemplateLoader::getInstance()->setPackageName('modules_website')->setDirectory('tests/')->setMimeContentType('html')->load('change-menu-basic');
		$template->setAttribute('menu', $menu);
		$html = $template->execute();
		$xmlDoc = new DOMDocument();
		$xmlDoc->formatOutput = true;
		$xmlDoc->preserveWhiteSpace = false;
		$this->assertTrue($xmlDoc->loadXML('<?xml version="1.0"?>'."\n".$html));
		$xpath = new DOMXPath($xmlDoc);

		// Position in XPath begins at 1, not 0!!
		$this->assertEquals(5, $xpath->query('/ul/li')->length);
		$this->assertEquals(3, $xpath->query('/ul/li[1]/ul/li')->length);
		$this->assertEquals(2, $xpath->query('/ul/li[3]/ul/li')->length);
		$this->assertEquals(2, $xpath->query('/ul/li[4]/ul/li')->length);
		$this->assertEquals(4, $xpath->query('/ul/li[4]/ul/li[1]/ul/li')->length);
		$this->assertEquals(1, $xpath->query('/ul/li[4]/ul/li[2]/ul/li')->length);
	}


	public function testTalChangeMenuClass()
	{
		$ws = website_WebsiteModuleService::getInstance();

		$menu = $ws->getMenuByTag('menu-main');
		$template = TemplateLoader::getInstance()->setPackageName('modules_website')->setDirectory('tests/')->setMimeContentType('html')->load('change-menu-class');
		$template->setAttribute('menu', $menu);
		$html = $template->execute();
		$xmlDoc = new DOMDocument();
		$xmlDoc->formatOutput = true;
		$xmlDoc->preserveWhiteSpace = false;
		$this->assertTrue($xmlDoc->loadXML('<?xml version="1.0"?>'."\n".$html));
		$xpath = new DOMXPath($xmlDoc);
		$liNodeList = $xpath->query('//li');
		$this->assertEquals('liItem first', $liNodeList->item(0)->getAttribute('class'));
		$this->assertEquals('liItem last', $liNodeList->item($liNodeList->length - 1)->getAttribute('class'));
		$this->assertEquals(count($menu)-2, $xpath->query('//li[@class="liItem"]')->length);
		$this->assertEquals(6, $xpath->query('//ul[@class="ulContainer"]')->length);
	}


	public function testTalChangeMenuEvenOdd()
	{
		$ws = website_WebsiteModuleService::getInstance();

		$menu = $ws->getMenuByTag('menu-main');
		$template = TemplateLoader::getInstance()->setPackageName('modules_website')->setDirectory('tests/')->setMimeContentType('html')->load('change-menu-evenodd');
		$template->setAttribute('menu', $menu);
		$html = $template->execute();
		$xmlDoc = new DOMDocument();
		$xmlDoc->formatOutput = true;
		$xmlDoc->preserveWhiteSpace = false;
		$this->assertTrue($xmlDoc->loadXML('<?xml version="1.0"?>'."\n".$html));
		$xpath = new DOMXPath($xmlDoc);

		$this->assertEquals(3, $xpath->query('/ul/li[@class="even"]')->length);
		$this->assertEquals(2, $xpath->query('/ul/li[@class="odd"]')->length);
		$this->assertEquals(2, $xpath->query('/ul/li[1]/ul/li[@class="even"]')->length);
		$this->assertEquals(1, $xpath->query('/ul/li[1]/ul/li[@class="odd"]')->length);
	}


	public function testTalChangeMenuCurrent()
	{
		$ws = website_WebsiteModuleService::getInstance();
		$ws->setCurrentPageId(133);

		$menu = $ws->getMenuByTag('menu-main');
		$template = TemplateLoader::getInstance()->setPackageName('modules_website')->setDirectory('tests/')->setMimeContentType('html')->load('change-menu-current');
		$template->setAttribute('menu', $menu);
		$html = $template->execute();
		$xmlDoc = new DOMDocument();
		$xmlDoc->formatOutput = true;
		$xmlDoc->preserveWhiteSpace = false;
		$this->assertTrue($xmlDoc->loadXML('<?xml version="1.0"?>'."\n".$html));
		$xpath = new DOMXPath($xmlDoc);
		$this->assertEquals(1, $xpath->query('//li[@class="current"]')->length);
		$this->assertEquals('current', $xpath->query('/ul/li[4]/ul/li[1]/ul/li[2]')->item(0)->getAttribute('class'));
	}


	public function testTalChangeMenuInPath()
	{
		$ws = website_WebsiteModuleService::getInstance();

		$menu = $ws->getMenuByTag('menu-main');
		$template = TemplateLoader::getInstance()->setPackageName('modules_website')->setDirectory('tests/')->setMimeContentType('html')->load('change-menu-inpath');
		$template->setAttribute('menu', $menu);
		$html = $template->execute();
		$xmlDoc = new DOMDocument();
		$xmlDoc->formatOutput = true;
		$xmlDoc->preserveWhiteSpace = false;
		$this->assertTrue($xmlDoc->loadXML('<?xml version="1.0"?>'."\n".$html));
		$xpath = new DOMXPath($xmlDoc);

		$this->assertEquals(3, $xpath->query('//li[@class="inPath"]')->length);
		$this->assertEquals('inPath', $xpath->query('/ul/li[4]')->item(0)->getAttribute('class'));
		$this->assertEquals('inPath', $xpath->query('/ul/li[4]/ul/li[1]')->item(0)->getAttribute('class'));
		$this->assertEquals('inPath', $xpath->query('/ul/li[4]/ul/li[1]/ul/li[2]')->item(0)->getAttribute('class'));
	}


	public function testTalChangeMenuClassByLevel()
	{
		$ws = website_WebsiteModuleService::getInstance();

		$menu = $ws->getMenuByTag('menu-main');
		$template = TemplateLoader::getInstance()->setPackageName('modules_website')->setDirectory('tests/')->setMimeContentType('html')->load('change-menu-classByLevel');
		$template->setAttribute('menu', $menu);
		$html = $template->execute();
		$xmlDoc = new DOMDocument();
		$xmlDoc->formatOutput = true;
		$xmlDoc->preserveWhiteSpace = false;
		$this->assertTrue($xmlDoc->loadXML('<?xml version="1.0"?>'."\n".$html));
		$xpath = new DOMXPath($xmlDoc);

		// 4 +1 that contains "FirstInLevel" +1 that contains "LastInLevel"
		$this->assertEquals(3, $xpath->query('/ul/li[@class="first"]')->length);
		$this->assertEquals(1, $xpath->query('/ul/li[@class="first FirstInLevel"]')->length);
		$this->assertEquals(1, $xpath->query('/ul/li[@class="first LastInLevel"]')->length);
		$this->assertEquals($xpath->query('/ul/li')->length - 1 - 1, $xpath->query('/ul/li[@class="first"]')->length);

		// 1 +3 that contains "FirstInLevel" +3 that contains "LastInLevel"
		$this->assertEquals(1, $xpath->query('/ul/li/ul/li[@class="second"]')->length);
		$this->assertEquals(3, $xpath->query('/ul/li/ul/li[@class="second FirstInLevel"]')->length);
		$this->assertEquals(3, $xpath->query('/ul/li/ul/li[@class="second LastInLevel"]')->length);
		// -6 : there are 6 items of level 2
		$this->assertEquals($xpath->query('/ul/li/ul/li')->length - 6, $xpath->query('/ul/li/ul/li[@class="second"]')->length);

		// 2 +1 that contains "FirstInLevel" +1 that contains "LastInLevel" +1 that contains "FirstInLevel LastInLevel"
		$this->assertEquals(2, $xpath->query('/ul/li/ul/li/ul/li[@class="third"]')->length);
		$this->assertEquals(1, $xpath->query('/ul/li/ul/li/ul/li[@class="third FirstInLevel"]')->length);
		$this->assertEquals(1, $xpath->query('/ul/li/ul/li/ul/li[@class="third LastInLevel"]')->length);
		$this->assertEquals(1, $xpath->query('/ul/li/ul/li/ul/li[@class="third FirstInLevel LastInLevel"]')->length);
		// -2 : there are 2 items of level 3
		$this->assertEquals($xpath->query('/ul/li/ul/li/ul/li')->length - 3, $xpath->query('/ul/li/ul/li/ul/li[@class="third"]')->length);

		$this->assertEquals(3, $xpath->query('/ul/li/ul[@class="ulSecond"]')->length);
		$this->assertEquals($xpath->query('/ul/li/ul[@class="ulSecond"]')->length, $xpath->query('/ul/li/ul')->length);

	}


	public function testTalChangeMenuReplacements()
	{
		$ws = website_WebsiteModuleService::getInstance();

		$menu = $ws->getMenuByTag('menu-main');
		$template = TemplateLoader::getInstance()
			->setPackageName('modules_website')
			->setDirectory('tests/')
			->setMimeContentType('html')
			->load('change-menu-replacements');
		$template->setAttribute('menu', $menu);
		$html = $template->execute();
		$xmlDoc = new DOMDocument();
		$xmlDoc->formatOutput = true;
		$xmlDoc->preserveWhiteSpace = false;
		$this->assertTrue($xmlDoc->loadXML('<?xml version="1.0"?>'."\n".$html));
		$xpath = new DOMXPath($xmlDoc);

		// Check first level <li/>
		for ($i = 0 ; $i < 5 ; $i++)
		{
			$this->assertEquals(1, $xpath->query('/ul/li['.($i+1).'][@class="level_0 position_'.$i.'"]')->length);
			// Check second level <li/> for first entry
			if ($i == 0)
			{
				for ($j = 0 ; $j < 3 ; $j++)
				{
					$this->assertEquals(1, $xpath->query('/ul/li['.($i+1).']/ul/li['.($j+1).'][@class="level_1 position_'.$j.'"]')->length);
				}
			}
		}
	}


	public function testTalChangeMenuOnlyTopics()
	{
		$ws = website_WebsiteModuleService::getInstance();

		$menu = $ws->getMenuByTag('menu-main');
		$template = TemplateLoader::getInstance()
			->setPackageName('modules_website')
			->setDirectory('tests/')
			->setMimeContentType('html')
			->load('change-menu-onlytopics');
		$template->setAttribute('menu', $menu);
		$html = $template->execute();
		$xmlDoc = new DOMDocument();
		$xmlDoc->formatOutput = true;
		$xmlDoc->preserveWhiteSpace = false;
		$this->assertTrue($xmlDoc->loadXML('<?xml version="1.0"?>'."\n".$html));
		$xpath = new DOMXPath($xmlDoc);

		$this->assertEquals(5, $xpath->query('/ul/li')->length);
		$this->assertEquals(1, $xpath->query('/ul/li/ul')->length);
		$this->assertEquals(2, $xpath->query('/ul/li/ul/li')->length);
	}
}
