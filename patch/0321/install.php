<?php
/**
 * website_patch_0321
 * @package modules.website
 */
class website_patch_0321 extends patch_BasePatch
{
	/**
	 * Entry point of the patch execution.
	 */
	public function execute()
	{
		// FIX #36064
		$this->log("=== FIX #36064 ===");
		$this->log("compile-locales website");
		$this->execChangeCommand("compile-locales", array("website"));
		
		$this->log("compile-blocks");
		$this->execChangeCommand("compile-blocks");
		
		$this->log("migrage page contents");
		$rc = RequestContext::getInstance();
		$pageService = website_PageService::getInstance();
		$scriptPath = "modules/website/patch/0321/migratePageContent.php";
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
		
		// FIX #35085
		$this->log("=== FIX #35085 ===");
		$newPageGroup = f_util_FileUtils::buildWebeditPath("modules/website/patch/0321/page-group.png");
		$oldPageGroup = f_util_FileUtils::buildWebeditPath("libs/icons/small/page-group.png");
		try
		{
			f_util_FileUtils::cp($newPageGroup, $oldPageGroup, f_util_FileUtils::OVERRIDE);
		}
		catch (Exception $e)
		{
			$this->logWarning("Could not override libs/icons/small/page-group.png please do it manually using ".$newPageGroup);
		}
		
		$newPageGroupIndex = f_util_FileUtils::buildWebeditPath("modules/website/patch/0321/page-group-index.png");
		$oldPageGroupIndex = f_util_FileUtils::buildWebeditPath("libs/icons/small/page-group-index.png");
		try
		{
			f_util_FileUtils::cp($newPageGroupIndex, $oldPageGroupIndex, f_util_FileUtils::OVERRIDE);
		}
		catch (Exception $e)
		{
			$this->logWarning("Could not create libs/icons/small/page-group-index.png please do it manually using ".$newPageGroupIndex);
		}
		
		$newPageGroupHome = f_util_FileUtils::buildWebeditPath("modules/website/patch/0321/page-group-home.png");
		$oldPageGroupHome = f_util_FileUtils::buildWebeditPath("libs/icons/small/page-group-home.png");
		try
		{
			f_util_FileUtils::cp($newPageGroupHome, $oldPageGroupHome, f_util_FileUtils::OVERRIDE);
		}
		catch (Exception $e)
		{
			$this->logWarning("Could not create libs/icons/small/page-group-home.png please do it manually using ".$newPageGroupHome);
		}
		
		$this->log("clear-webapp-cache");
		$this->execChangeCommand("clear-webapp-cache");
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
		return '0321';
	}
}