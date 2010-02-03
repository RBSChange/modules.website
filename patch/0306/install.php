<?php
/**
 * website_patch_0306
 * @package modules.website
 */
class website_patch_0306 extends patch_BasePatch
{
	/**
	 * Entry point of the patch execution.
	 */
	public function execute()
	{
		parent::execute();
		$this->executeSQLQuery("ALTER TABLE `m_website_doc_topic_i18n` ADD `document_publicationstatus_i18n` ENUM('DRAFT', 'CORRECTION', 'ACTIVE', 'PUBLICATED', 'DEACTIVATED', 'FILED', 'DEPRECATED', 'TRASH', 'WORKFLOW') NULL DEFAULT NULL");
		$this->executeSQLQuery("UPDATE `m_website_doc_topic_i18n` SET `document_publicationstatus_i18n` = (SELECT t.`document_publicationstatus` FROM `m_website_doc_topic` AS t WHERE t.document_id = m_website_doc_topic_i18n.document_id)");
		
		$this->executeSQLQuery("UPDATE m_website_doc_page SET navigationvisibility = 0 WHERE document_id IN (SELECT indexpage FROM m_website_doc_topic WHERE indexpage is not null)");
		$this->executeSQLQuery("UPDATE m_website_doc_page_i18n SET navigationvisibility_i18n = 0 WHERE document_id IN (SELECT indexpage FROM m_website_doc_topic WHERE indexpage is not null)");
		
		$this->executeSQLQuery("ALTER TABLE `m_website_doc_menu` DROP `menuitemserialized`");
	
		$this->executeSQLQuery("ALTER TABLE `m_website_doc_topic` DROP `urllabel`");
		$this->executeSQLQuery("ALTER TABLE `m_website_doc_topic_i18n` DROP `urllabel_i18n`");
		
		$topics = website_TopicService::getInstance()->createQuery()->find();
		foreach ($topics as $topic) 
		{
			$this->updateTopicStatus($topic);
		}
		echo "\n";
	}
	
	/**
	 * @param website_persistentdocument_topic $topic
	 */
	private function updateTopicStatus($topic)
	{
		$rc = RequestContext::getInstance();
		foreach ($topic->getI18nInfo()->getLangs() as $lang) 
		{
			try 
			{
				echo "\nUpdate topic " . $topic->getId() .'-'.$lang;
				$rc->beginI18nWork($lang);
				$topic->setModificationdate(null);
				$topic->setPublicationstatus('ACTIVE');
				$topic->save();	
				echo " -> " . $topic->getPublicationstatus();
				$rc->endI18nWork();
			}
			catch (Exception $e)
			{
				$rc->endI18nWork($e);
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
		return '0306';
	}
}