<?php
/**
 * website_ListBlocktemplatesService
 * @package modules.website.lib.services
 */
class website_ListBlocktemplatesService extends change_BaseService implements list_ListItemsService
{
	/**
	 * @var website_ListBlocktemplatesService
	 */
	private static $instance;

	/**
	 * @var array<list_Item>
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
			$ls = LocaleService::getInstance();
			$lm = array('ucf');
			$this->itemArray[$templatePrefix] = array();
			
			// Handle global templates.
			$namesToIgnore = array('Error', 'Input');
			$paths = FileResolver::getInstance()->setPackageName('modules_' . $blockModule)->getPaths('templates');
			foreach ($paths as $path)
			{
				$dir = dir($path);
				while (false !== ($entry = $dir->read()))
				{
					if (f_util_StringUtils::beginsWith($entry, $templatePrefix, f_util_StringUtils::CASE_SENSITIVE) && f_util_StringUtils::endsWith($entry, '.all.all.html', f_util_StringUtils::CASE_SENSITIVE))
					{
						$value = str_replace($templatePrefix, '', str_replace('.all.all.html', '', $entry));
						if (in_array($value, $namesToIgnore))
						{
							continue;
						}
						$label = $ls->transBO('m.' . $blockModule . '.list.blocktemplates-' . strtolower($value), $lm);
						$this->itemArray[$templatePrefix][$value] = new list_Item($label, $value);
						$namesToIgnore[] = $value;
					}
				}
				$dir->close();
			}
			
			// Handle theme-specific templates.
			foreach (theme_ThemeService::getInstance()->createQuery()->find() as $theme)
			{
				/* @var $theme theme_persistentdocument_theme */
				$themeCode = $theme->getCodename();
				$themesByCode[$themeCode] = $theme;
				$paths = FileResolver::getInstance()->setPackageName('themes_' . $themeCode)->getPaths('modules'. DIRECTORY_SEPARATOR . $blockModule . DIRECTORY_SEPARATOR . 'templates');
				if (is_array($paths))
				{
					foreach ($paths as $path)
					{
						$dir = dir($path);
						while (false !== ($entry = $dir->read()))
						{
							if (f_util_StringUtils::beginsWith($entry, $templatePrefix, f_util_StringUtils::CASE_SENSITIVE) && f_util_StringUtils::endsWith($entry, '.all.all.html', f_util_StringUtils::CASE_SENSITIVE))
							{
								$value = str_replace($templatePrefix, '', str_replace('.all.all.html', '', $entry));
								if (in_array($value, $namesToIgnore))
								{
									continue;
								}
								$label = $ls->transBO('t.' . $themeCode . '.list.blocktemplates-' . strtolower($value), $lm) . ' (' . $theme->getLabel() . ')';
								$this->itemArray[$templatePrefix][] = new list_Item($label, $value);
							}
						}
						$dir->close();
					}
				}
			}
		}
		return $this->itemArray[$templatePrefix];
	}

	/**
	 * @var array
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