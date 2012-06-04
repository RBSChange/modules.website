<?php
/**
 * @date Wed Feb 28 12:25:05 CET 2007
 * @author INTbonjF
 */
class website_MenuService extends f_persistentdocument_DocumentService
{
	/**
	 * @var website_MenuService
	 */
	private static $instance;

	/**
	 * @return website_MenuService
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
	 * @return website_persistentdocument_menu
	 */
	public function getNewDocumentInstance()
	{
		return $this->getNewDocumentInstanceByModelName('modules_website/menu');
	}

	/**
	 * Create a query based on 'modules_website/menu' model
	 * @return f_persistentdocument_criteria_Query
	 */
	public function createQuery()
	{
		return $this->pp->createQuery('modules_website/menu');
	}


	/**
	 * @param website_persistentdocument_menu $document
	 * @param Integer $parentNodeId Parent node ID where to save the document (optionnal).
	 * @return void
	 */
	protected function preInsert($document, $parentNodeId)
	{
		if (is_integer($parentNodeId) && ! DocumentHelper::getDocumentInstance($parentNodeId) instanceof website_persistentdocument_menufolder)
		{
			throw new Exception('A "menu" can only be created inside a "menufolder".');
		}
	}

	/**
	 * @param notification_persistentdocument_notification $document
	 * @param array<string, string> $attributes
	 * @param integer $mode
	 * @param string $moduleName
	 */
	public function completeBOAttributes($document, &$attributes, $mode, $moduleName)
	{
		if ($mode & DocumentHelper::MODE_CUSTOM)
		{
			$ts = TagService::getInstance();
			$label = array();
			foreach ($ts->getTagObjects($document) as $tagObject)
			{
				if ($ts->isContextualTag($tagObject->getValue()))
				{
					$label[] = $tagObject->getLabel();
				}
			}
			if (f_util_ArrayUtils::isEmpty($label))
			{
				$label[] = LocaleService::getInstance()->trans('m.website.bo.general.no-tag-available');
			}
			$attributes['tagLabel'] = join(', ', $label);
		}
	}
	
	/**
	 * @param website_persistentdocument_menu $document
	 * @return website_MenuEntry|null
	 */
	public function getMenuEntry($document)
	{
		$entry = website_MenuEntry::getNewInstance();
		$entry->setDocument($document);
		$entry->setLabel($document->getLabel());
		$entry->setContainer(true);
		return $entry;
	}
	
	/**
	 * @param website_persistentdocument_menu $document
	 * @return website_persistentdocument_menuitem[]
	 */
	public function getChildrenDocumentsForMenu($document)
	{
		return $document->getPublishedMenuItemArray();
	}
}