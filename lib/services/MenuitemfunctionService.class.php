<?php
/**
 * @date Mon, 11 Jun 2007 15:30:47 +0200
 * @author intbonjf
 */
class website_MenuitemfunctionService extends website_MenuitemService
{
	/**
	 * @var website_MenuitemfunctionService
	 */
	private static $instance;

	/**
	 * @return website_MenuitemfunctionService
	 */
	public static function getInstance()
	{
		if (self::$instance === null)
		{
			self::$instance = new self();
		}
		return self::$instance;
	}

	/**
	 * @return website_persistentdocument_menuitemfunction
	 */
	public function getNewDocumentInstance()
	{
		return $this->getNewDocumentInstanceByModelName('modules_website/menuitemfunction');
	}

	/**
	 * Create a query based on 'modules_website/menuitemfunction' model
	 * @return f_persistentdocument_criteria_Query
	 */
	public function createQuery()
	{
		return $this->pp->createQuery('modules_website/menuitemfunction');
	}

	/**
	 * @param website_persistentdocument_menuitemfunction $document
	 * @param array<string, string> $attributes
	 * @param integer $mode
	 * @param string $moduleName
	 */
	public function completeBOAttributes($document, &$attributes, $mode, $moduleName)
	{
		if ($mode & DocumentHelper::MODE_CUSTOM)
		{
			$attributes['refers-to'] = $document->getUrl();
			$attributes['popup'] = LocaleService::getInstance()->trans('m.generic.backoffice.no', array('ucf'));
		}
	}
	
	/**
	 * @param website_persistentdocument_menuitemfunction $document
	 * @return website_MenuEntry|null
	 */
	public function getMenuEntry($document)
	{
		$entry = website_MenuEntry::getNewInstance();
		$entry->setDocument($document);
		$entry->setLabel($document->getLabel());
		
		$url = $document->getUrl();
		if (f_util_StringUtils::beginsWith($url, 'function:'))
		{
			$menuFunctionClass = 'website_MenuItem' . ucfirst(substr($url, 9)) . 'Function';
			if (f_util_ClassUtils::classExists($menuFunctionClass))
			{
				f_util_ClassUtils::callMethodArgs($menuFunctionClass, 'execute', array($entry));
			}
		}
		else
		{
			$entry->setUrl($url);
		}
		return $entry;
	}
}