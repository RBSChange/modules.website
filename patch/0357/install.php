<?php
/**
 * website_patch_0357
 * @package modules.website
 */
class website_patch_0357 extends patch_BasePatch
{ 
	/**
	 * Entry point of the patch execution.
	 */
	public function execute()
	{
		
		$sqlFilePath = f_util_FileUtils::buildChangeBuildPath('modules', 'website', 'dataobject','m_website_doc_menu_i18n.mysql.sql');
		if (!file_exists($sqlFilePath))
		{
			$this->log('Compile Documents...');
			$this->execChangeCommand('compile-documents');
		}
		$sql = file_get_contents($sqlFilePath);
		$this->executeSQLQuery($sql);
		
		$this->execChangeCommand('compile-editors-config');
		
		$stmt = $this->executeSQLSelect("SELECT COUNT(*) AS NB FROM `m_website_doc_menu_i18n`");
		$result = $stmt->fetchAll();
		if (count($result) && intval($result[0]['NB']) == 0)
		{
			$this->log('Fill m_website_doc_menu_i18n table...');
			$sql = "INSERT INTO `m_website_doc_menu_i18n` (`document_id`, `lang_i18n`, `document_label_i18n`, `document_publicationstatus_i18n`) SELECT `document_id`, `document_lang`, `document_label` , `document_publicationstatus` FROM `m_website_doc_menu`";
			$this->executeSQLQuery($sql);
		}
	}
}