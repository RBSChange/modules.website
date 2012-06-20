<?php
/**
 * @package modules.website
 * @method website_ListTemplatesService getInstance()
 */
class website_ListStylesheetsService extends change_BaseService implements list_ListItemsService
{
	/**
	 * Returns an array of available stylesheets for the website module.
	 * @return list_Item[]
	 */
	public function getItems()
	{
		$items = array();
		foreach ($this->getWebsiteAndTopicStylesheets() as $fileName => $label)
		{
			$items[] = new list_Item($label, $fileName);
		}
		return $items;
	}
	
	/**
	 * Stylesheets names reserved for internal used (won't be displayed in stylesheets list).
	 * @var array
	 */
	private static $systemStylesheets = array('backoffice', 'print', 'bindings', 'frontoffice', 'richtext');
	
	/**
	 * @return array<string, string>
	 */
	protected function getWebsiteAndTopicStylesheets()
	{
		$availablePaths = FileResolver::getInstance()->setPackageName('modules_website')->setDirectory('style')->getPaths('');
		
		$styles = array();
		
		foreach ($availablePaths as $availablePath)
		{
			if (is_dir($availablePath))
			{
				$dh = opendir($availablePath);
				if ($dh)
				{
					while (($file = readdir($dh)) !== false)
					{
						$fileMatch = array();
						if (preg_match('/^((?:website|topic)[a-zA-Z0-9_-]+)\.css$/', $file, $fileMatch))
						{
							$fileName = $fileMatch[1];
							if (!in_array($fileName, self::$systemStylesheets))
							{
								$styles[$fileName] = LocaleService::getInstance()->trans('m.website.bo.styles.' . $fileName, array('ucf'));
							}
						}
					}
					closedir($dh);
				}
			}
		}
		return $styles;
	}
}