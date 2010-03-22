<?php
/**
 * @date Mon, 11 Jun 2007 15:30:42 +0200
 * @author intbonjf
 */
class website_MenuitemdocumentService extends website_MenuitemService
{
	/**
	 * @var website_MenuitemdocumentService
	 */
	private static $instance;

	/**
	 * @return website_MenuitemdocumentService
	 */
	public static function getInstance()
	{
		if (self::$instance === null)
		{
			self::$instance = self::getServiceClassInstance(get_class());
		}
		return self::$instance;
	}

	/**
	 * @return website_persistentdocument_menuitemdocument
	 */
	public function getNewDocumentInstance()
	{
		return $this->getNewDocumentInstanceByModelName('modules_website/menuitemdocument');
	}

	/**
	 * Create a query based on 'modules_website/menuitemdocument' model
	 * @return f_persistentdocument_criteria_Query
	 */
	public function createQuery()
	{
		return $this->pp->createQuery('modules_website/menuitemdocument');
	}
	
	/**
	 * @param f_persistentdocument_PersistentDocument $document
	 * @return Integer
	 */
	function deleteByDocument($document)
	{
		return $this->createQuery()->add(Restrictions::eq("document", $document))->delete();
	}
	
	/**
	 * @param website_persistentdocument_page $document
	 * @param string $forModuleName
	 * @return array
	 */
	public function getResume($document, $forModuleName)
	{
		$data = parent::getResume($document, $forModuleName, array('properties' => true, 'publication' => true, 'history' => true));
		$itemDoc = $document->getDocument();
		$data['content'] = array(
			'editMenuitemDocument' => array('id' => $itemDoc->getId(),
			'module' => $itemDoc->getPersistentModel()->getModuleName(),
			'type' => str_replace('/', '_', $itemDoc->getDocumentModelName()),
			'label' => $itemDoc->getLabel())
		);
		
		return $data;
	}

	/**
	 * @param website_persistentdocument_menuitemdocument $document
	 * @return void
	 */
	protected function preSave($document)
	{
		$this->refreshLabel($document);
	}
	
	/**
	 * @param f_persistentdocument_Document $document
	 */
	public function synchronizeLabelForRelatedMenuItems($document)
	{
		$document = DocumentHelper::getByCorrection($document);
		$query = $this->createQuery()->add(Restrictions::eq('document', $document));
		foreach ($query->find() as $menuitem)
		{
			$menuitem->setLabel($document->getLabel());
			$menuitem->save();
		}
	}
	
	/**
	 * @param f_persistentdocument_Document $document
	 */
	public function removeTranslationForRelatedMenuItems($document)
	{
		$document = DocumentHelper::getByCorrection($document);
		$query = $this->createQuery()->add(Restrictions::eq('document', $document));
		foreach ($query->find() as $menuitem)
		{
			$menuitem->delete();
		}
	}
	
	/**
	 * @param website_persistentdocument_menuitemdocument $menuitem
	 */
	protected function refreshLabel($menuitem)
	{
		$pageOrTopic = $menuitem->getDocument();
		if ($pageOrTopic !== null)
		{
			// Of course this is not the same name ... pff.
			$rc = RequestContext::getInstance();
			foreach ($pageOrTopic->getI18nInfo()->getLangs() as $lang)
			{
				try
				{
					$rc->beginI18nWork($lang);
					if ($pageOrTopic instanceof website_persistentdocument_page)
					{
						$label = $pageOrTopic->getNavigationtitle();
					}
					else
					{
						$label = $pageOrTopic->getLabel();
					}
					$menuitem->setLabel($label);
					$rc->endI18nWork();
				}
				catch (Exception $e)
				{
					$rc->endI18nWork($e);
				}
			}
		}
	}
}