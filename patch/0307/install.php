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
		
		$to = f_util_FileUtils::buildWebeditPath('media', 'frontoffice', 'favicon.ico');
		$path = f_util_FileUtils::buildDocumentRootPath('favicon.ico');
		if (file_exists($path))
		{
			if (file_exists($to))
			{
				unlink($to);
			}
			rename($path, $to);
		}
		else if (!file_exists($to))
		{
			$from = f_util_FileUtils::buildFrameworkPath('builder', 'home', 'media', 'frontoffice', 'favicon.ico');
			f_util_FileUtils::cp($from, $to);
		}
		
		$to = f_util_FileUtils::buildWebeditPath('media', 'frontoffice', 'robots.txt');
		$path = f_util_FileUtils::buildDocumentRootPath('robots.txt');
		if (file_exists($path))
		{
			if (file_exists($to))
			{
				unlink($to);
			}
			rename($path, $to);
		}
		else if (!file_exists($to))
		{
			$from = f_util_FileUtils::buildFrameworkPath('builder', 'home', 'media', 'frontoffice', 'robots.txt');
			f_util_FileUtils::cp($from, $to);
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