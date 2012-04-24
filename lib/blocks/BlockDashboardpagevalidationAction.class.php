<?php
class website_BlockDashboardpagevalidationAction extends dashboard_BlockDashboardAction
{	
	/**
	 * @param f_mvc_Request $request
	 * @param boolean $forEdition
	 */
	protected function setRequestContent($request, $forEdition)
	{
		if ($forEdition)
		{
			return;
		}
		
		$tasks = website_PageService::getInstance()->getPendingTasksForCurrentUser();
		if (count($tasks) > 0)
		{
			$taskAttr = array();
			foreach ($tasks as $task)
			{
				$document = DocumentHelper::getDocumentInstance($task->getWorkitem()->getDocumentid());			
				$attr = array(
					'id' => $task->getId(),
					'taskLabel' => LocaleService::getInstance()->transBO('m.website.bo.dashboard.task-label-validate', array('ucf'), array('author' => $task->getDescriptionAsHtml())),
					'dialog' => $task->getDialogName(),
					'module' => $task->getModule(),
					'status' => date_Formatter::toDefaultDateTimeBO($task->getUICreationdate()),
					'documentId' => $document->getId(),
				    'documentLabel' => f_util_HtmlUtils::textToHtml($document->getPersistentModel()->isLocalized() ? $document->getLabelForLang($task->getLang()) : $document->getLabel()),
					'documentThread' => f_util_HtmlUtils::textToHtml($document->getDocumentService()->getPathOf($document)),
					'comment' => $task->getCommentaryAsHtml(),
					'author' => ucfirst($task->getDescriptionAsHtml())
				);
				$taskAttr[] = $attr;
			}
			$request->setAttribute('tasks', $taskAttr);
		}
	}
}