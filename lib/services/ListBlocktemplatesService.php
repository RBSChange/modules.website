<?php
/**
 * website_ListBlocktemplatesService
 * @package modules.website.lib.services
 */
class website_ListBlocktemplatesService extends BaseService implements list_ListItemsService
{
	/**
	 * @var website_ListBlocktemplatesService
	 */
	private static $instance;

	/**
	 * @var Array<list_Item>
	 */
	private $itemArray = array();
	
	/**
	 * @return website_ListBlocktemplatesService
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
	 * @see list_persistentdocument_dynamiclist::getItems()
	 * @return list_Item[]
	 */
	public final function getItems()
	{
		$request = change_Controller::getInstance()->getContext()->getRequest();
		if (!$request->hasParameter('blockModule'))
		{
			Framework::info(__METHOD__ . ' Missing blockModule parameter');
			return array();
		}
		$blockModule = $request->getParameter('blockModule');
		if (!$request->hasParameter('blockName'))
		{
			Framework::info(__METHOD__ . ' Missing blockName parameter');
			return array();
		}
		$blockName = $request->getParameter('blockName');		
		
		$templatePrefix = ucfirst($blockModule) . '-Block-' . ucfirst($blockName) . '-';
		if (!isset($this->itemArray[$templatePrefix]))
		{
			$this->itemArray[$templatePrefix] = array();
			$paths = FileResolver::getInstance()->setPackageName('modules_' . $blockModule)->getPaths('templates');
			$ls = LocaleService::getInstance();
			foreach ($paths as $path)
			{
				$dir = dir($path);
				while (false !== ($entry = $dir->read()))
				{
					if (f_util_StringUtils::beginsWith($entry, $templatePrefix, f_util_StringUtils::CASE_SENSITIVE)
						&& f_util_StringUtils::endsWith($entry, '.all.all.html', f_util_StringUtils::CASE_SENSITIVE))
					{
						$value = str_replace($templatePrefix, '', str_replace('.all.all.html', '', $entry));
						if ($value != 'Error')
						{    
						    $label = $ls->transBO('m.' . $blockModule . '.list.blocktemplates-' . strtolower($value), array('ucf'));
						    $this->itemArray[$templatePrefix][] = new list_Item($label, $value);
						}
					}
				}
				$dir->close();
			}
		}
		return $this->itemArray[$templatePrefix];
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