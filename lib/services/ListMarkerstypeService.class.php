<?php
class website_ListMarkerstypeService extends change_BaseService implements list_ListItemsService
{
	/**
	 * @var website_ListMarkerstypeService
	 */
	private static $instance;
	

	/**
	 * @return website_ListMarkerstypeService
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
	 * Returns an array of available templates for the website module.
	 * @return list_Item[]
	 */
	public function getItems()
	{
		website_MarkerService::getInstance()->getMarkerTypeList();
		$items = array();
		foreach (website_MarkerService::getInstance()->getMarkerTypeList() as $markerModelName)
		{
			$model = f_persistentdocument_PersistentDocumentModel::getInstanceFromDocumentModelName($markerModelName);
			$label = LocaleService::getInstance()->transBO('modules.'.$model->getModuleName().'.document.'.$model->getDocumentName().'.document-name', array('ucf', 'html'));
			$items[] = new list_Item($label, $markerModelName);
		}
		return $items;
	}
	
	/**
	 * @param list_Item $a
	 * @param list_Item $b
	 */
	public function sortItem($a, $b)
	{
		if ($a->getLabel() === $b->getLabel())
		{
			return 0;		
		}
		return ($a->getLabel() > $b->getLabel()) ? 1 : -1;
	}	
}