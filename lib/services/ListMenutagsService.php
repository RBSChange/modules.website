<?php
/**
 * website_ListMenutagsService
 * @package modules.website.lib.services
 */
class website_ListMenutagsService extends BaseService implements list_ListItemsService
{
	/**
	 * @var website_ListMenutagsService
	 */
	private static $instance;

	/**
	 * @return website_ListMenutagsService
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
	 * @see list_persistentdocument_dynamiclist::getItems()
	 * @return list_Item[]
	 */
	public function getItems()
	{
		$items = array();
		foreach (TagService::getInstance()->getAvailableTagsInfoByModel('modules_website/menu') as $tag => $tagInfos)
		{
			if ($tagInfos['labeli18n'])
			{
				$label = LocaleService::getInstance()->transBO($tagInfos['labeli18n'], array('ucf'));
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