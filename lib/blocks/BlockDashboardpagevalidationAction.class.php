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
			try 
			{
				$document = DocumentHelper::getDocumentInstance($task->getWorkitem()->getDocumentid());
			}
			catch (Exception $e)
			{
				Framework::warn(__METHOD__ . ' no document found with id ' . $task->getWorkitem()->getDocumentid() .  ' for the task with id ' . $task->getId());
				continue;
			}
			
			$lastModification = date_Calendar::getInstance($task->getUICreationdate());
			if ($lastModification->isToday())
			{
				$status = $ls->transBO('m.uixul.bo.datepicker.calendar.today') . date_Formatter::format($lastModification, ', H:i');
			}
			else
			{
				$status = date_Formatter::toDefaultDateTimeBO($lastModification);
			}
			
			$attr = array(
				'id' => $task->getId(),
				'taskLabel' => $ls->transBO('m.website.bo.dashboard.task-label-validate', array('ucf'), array('author' => $task->getDescriptionAsHtml())),
				'dialog' => $task->getDialogName(),
				'module' => $task->getModule(),
				'status' => ucfirst($status),
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