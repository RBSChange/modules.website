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
		$ls = LocaleService::getInstance();
		$taskAttr = array();
		foreach ($tasks as $task)
		{
			$document = DocumentHelper::getDocumentInstanceIfExists($task->getWorkitem()->getDocumentid());
			if (!$document)
			{
				Framework::warn(__METHOD__ . ' no document found with id ' . $task->getWorkitem()->getDocumentid() .  ' for the task with id ' . $task->getId());
				continue;
			}
			
			$attr = array(
				'id' => $task->getId(),
				'taskLabel' => $ls->trans('m.website.bo.dashboard.task-label-validate', array('ucf'), array('author' => $task->getDescriptionAsHtml())),
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