<?php
/**
 * website_patch_0351
 * @package modules.website
 */
class website_patch_0351 extends patch_BasePatch
{
	/**
	 * Entry point of the patch execution.
	 */
	public function execute()
	{
		$this->log("compile-locales website");
		$this->execChangeCommand("compile-locales", array("website"));
		
		$this->log("compile-blocks");
		$this->execChangeCommand("compile-blocks");
		
		$this->log("migrage page contents");
		$rc = RequestContext::getInstance();
		$pageService = website_PageService::getInstance();
		$scriptPath = "modules/website/patch/0351/migratePageContent.php";
		foreach ($rc->getSupportedLanguages() as $lang)
		{
			$rc->beginI18nWork($lang);
			$ids = $pageService->createQuery()
				->setProjection(Projections::property("id", "i"))
				->add(Restrictions::like("content", "modules_website_iframe", MatchMode::ANYWHERE()))
				->findColumn("i");
			
			$idsCount = count($ids);
			$offset = 0;
			$chunkLength = 10;
			while ($offset < $idsCount)
			{
				$subIds = array_slice($ids, $offset, $chunkLength);
				$ret = f_util_System::execHTTPScript($scriptPath, array($lang, $subIds));
				if (!is_numeric($ret))
				{
					$this->logError("Error while processing " . $offset  . " - " . ($offset + $chunkLength) . ": $ret");
				}
				else
				{
					$this->log($offset . " - " . ($offset + $chunkLength) . " processed: " . $ret . " content updated ($lang)");
				}
				$offset += $chunkLength;
			}
			
			$rc->endI18nWork();
		}
	}

	/**
	 * @return String
	 */
	protected final function getModuleName()
	{
		return 'website';
	}

	/**
	 * @return String
	 */
	protected final function getNumber()
	{
		return '0351';
	}
}