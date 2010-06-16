<?php
/**
 * website_patch_0317
 * @package modules.website
 */
class website_patch_0317 extends patch_BasePatch
{
	/**
	 * Entry point of the patch execution.
	 */
	public function execute()
	{
		$this->executeSQLQuery("ALTER TABLE `m_website_doc_pageexternal_i18n` ADD `navigationtitle_i18n` VARCHAR(80)");
		$this->executeSQLQuery("UPDATE `m_website_doc_pageexternal_i18n` SET `navigationtitle_i18n` = (SELECT `navigationtitle` FROM `m_website_doc_pageexternal` WHERE m_website_doc_pageexternal.document_id = `m_website_doc_pageexternal_i18n`.document_id)");
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
		return '0317';
	}
}