<?php
/**
 * website_patch_0368
 * @package modules.website
 */
class website_patch_0368 extends patch_BasePatch
{
	/**
	 * Entry point of the patch execution.
	 */
	public function execute()
	{
		$list = list_StaticlistService::getInstance()->getByListId('modules_website/defaultstructure');
		if ($list)
		{
			$items = $list->getItems();
			foreach ($items as $index => $item)
			{
				/* @var $item list_StaticListItem */
				if ($item->getValue() == 'sample.xml')
				{
					unset($items[$index]);
				}
			}
			$list->setItemvalues(serialize(array_values($items)));
			$list->save();
		}
	}
}