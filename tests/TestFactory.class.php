<?php
class website_TestFactory extends website_TestFactoryBase
{
	/**
	 * @var website_TestFactory
	 */
	private static $instance;

	/**
	 * @return website_TestFactory
	 * @throws Exception
	 */
	public static function getInstance()
	{
		if (PROFILE != 'test')
		{
			throw new Exception('This method is only usable in test mode.');
		}
		if (self::$instance === null)
		{
			self::$instance = new website_TestFactory;
			// register the testFactory in order to be cleared after each test case.
			tests_AbstractBaseTest::registerTestFactory(self::$instance);
		}
		return self::$instance;
	}

	/**
	 * Clear the TestFactory instance.
	 * 
	 * @return void
	 * @throws Exception
	 */
	public static function clearInstance()
	{
		if (PROFILE != 'test')
		{
			throw new Exception('This method is only usable in test mode.');
		}
		self::$instance = null;
	}
	
	/**
	 * Initialize documents default properties
	 * @return void
	 */
	public function init()
	{
		$this->setMenuDefaultProperty('label', 'menu test');
		$this->setMenufolderDefaultProperty('label', 'menufolder test');
		$this->setMenuitemDefaultProperty('label', 'menuitem test');
		$this->setMenuitemdocumentDefaultProperty('label', 'menuitemdocument test');
		$this->setMenuitemfunctionDefaultProperty('label', 'menuitemfunction test');
		$this->setMenuitemtextDefaultProperty('label', 'menuitemtext test');
		
		$this->setWebsiteDefaultProperty('label', 'website test');
		$this->setWebsiteDefaultProperty('description', 'Testing website');
		$this->setWebsiteDefaultProperty('url', 'http://www.testwebsite1.com');
		$this->setWebsiteDefaultProperty('domain', 'www.testwebsite1.com');
		
		$this->setPageexternalDefaultProperty('label', 'pageexternal test');
		$this->setPagereferenceDefaultProperty('label', 'pagereference test');
		$this->setPageversionDefaultProperty('label', 'pageversion test');
		$this->setPreferencesDefaultProperty('label', 'preferences test');
		$this->setTemplateDefaultProperty('label', 'template test');
		
		$this->setTopicDefaultProperty('label', 'topic test');		
		
		$this->setPageDefaultProperty('label', 'page test');
		$this->setPageDefaultProperty('navigationtitle', 'page test');
		$this->setPageDefaultProperty('indexingstatus', false);
		$this->setPageDefaultProperty('template', 'tplFree');
		$this->setPageDefaultProperty('isIndexPage', false);
		$this->setPageDefaultProperty('isHomePage', false);		
		
	}


}