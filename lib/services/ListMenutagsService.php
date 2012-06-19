<?php
/**
 * @package modules.website
 * @method website_ListMenutagsService getInstance()
 */
class website_ListMenutagsService extends change_BaseService implements list_ListItemsService
{
	/**
	 * @see list_persistentdocument_dynamiclist::getItems()
	 * @return list_Item[]
	 */
	public final function getItems()
	{
		$items = array();
		foreach (TagService::getInstance()->getAvailableTagsInfoByModel('modules_website/menu') as $tag => $tagInfos)
		{
			if ($tagInfos['labeli18n'])
			{
				$label = LocaleService::getInstance()->trans($tagInfos['labeli18n'], array('ucf'));
			}
			else
			{
				$label = f_Locale::translate($tagInfos['label']);
			}
			$items[] = new list_Item($label, $tag);
		}
		return $items;
	}

	/**
	 * @var Array
	 */
	private $parameters = array();
	
	/**
	 * @see list_persistentdocument_dynamiclist::getListService()
	 * @param array $parameters
	 */
	public function setParameters($parameters)
	{
		$this->parameters = $parameters;
	}
}