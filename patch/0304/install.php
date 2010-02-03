<?php
/**
 * website_patch_0304
 * @package modules.website
 */
class website_patch_0304 extends patch_BasePatch
{
	//  by default, isCodePatch() returns false.
	//  decomment the following if your patch modify code instead of the database structure or content.
	/**
	 * Returns true if the patch modify code that is versionned.
	 * If your patch modify code that is versionned AND database structure or content,
	 * you must split it into two different patches.
	 * @return Boolean true if the patch modify code that is versionned.
	 */
	//	public function isCodePatch()
	//	{
	//		return true;
	//	}

	/**
	 * Entry point of the patch execution.
	 */
	public function execute()
	{
		$ps = website_PageService::getInstance();
		$rq = RequestContext::getInstance();
		foreach ($ps->createQuery()->find() as $page)
		{
			echo ".";
			foreach ($rq->getSupportedLanguages() as $lang)
			{
				try
				{
					$rq->beginI18nWork($lang);
					if ($page->isContextLangAvailable())
					{
						$ps->buildBlockMetaInfo($page);
					}
					$rq->endI18nWork();
				}
				catch (Exception $e)
				{
					$rq->endI18nWork($e);
					throw $e;
				}
			}
			$ps->saveMeta($page);
		}
		echo "\n";
	}

	/**
	 * Returns the name of the module the patch belongs to.
	 *
	 * @return String
	 */
	protected final function getModuleName()
	{
		return 'website';
	}

	/**
	 * Returns the number of the current patch.
	 * @return String
	 */
	protected final function getNumber()
	{
		return '0304';
	}
}
