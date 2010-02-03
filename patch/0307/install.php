<?php
/**
 * website_patch_0307
 * @package modules.website
 */
class website_patch_0307 extends patch_BasePatch
{
	/**
	 * Entry point of the patch execution.
	 */
	public function execute()
	{
		parent::execute();
		
		$this->executeSQLQuery("ALTER TABLE `m_website_doc_website` ADD `favicon` int(11) default NULL");
		$this->executeSQLQuery("ALTER TABLE `m_website_doc_website` ADD `document_s18s` mediumtext");

		//compile-documents
		//compile-db-schema
		
		$to = f_util_FileUtils::buildWebappPath('media', 'frontoffice', 'favicon.ico');
		if (!file_exists($to))
		{
			$path = f_util_FileUtils::buildWebappPath('www', 'favicon.ico');
			if (file_exists($path))
			{
				rename($path, $to);
			}
			else
			{
				$from = f_util_FileUtils::buildFrameworkPath('builder', 'webapp', 'media', 'frontoffice', 'favicon.ico');
				f_util_FileUtils::cp($from, $to);
			}
		}
		
		$to = f_util_FileUtils::buildWebappPath('media', 'frontoffice', 'robots.txt');
		if (!file_exists($to))
		{
			$path = f_util_FileUtils::buildWebappPath('www', 'robots.txt');
			if (file_exists($path))
			{
				rename($path, $to);
			}
			else
			{
				$from = f_util_FileUtils::buildFrameworkPath('builder', 'webapp', 'media', 'frontoffice', 'robots.txt');
				f_util_FileUtils::cp($from, $to);
			}
		}
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
		return '0307';
	}
}