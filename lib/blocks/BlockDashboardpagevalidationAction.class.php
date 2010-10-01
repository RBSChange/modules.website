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
				$lastModification = date_Calendar::getInstance($task->getCreationdate());
				
				if ($lastModification->isToday())
				{
					$status = f_Locale::translateUI('&modules.uixul.bo.datePicker.Calendar.today;') . date_DateFormat::format(date_Converter::convertDateToLocal($lastModification), ', H:i');
				}
				else
				{
					$status = date_DateFormat::format(date_Converter::convertDateToLocal($lastModification), 'l j F Y, H:i');
				}
				$attr = array(
					'id' => $task->getId(),
					'taskLabel' => f_Locale::translateUI('&modules.website.bo.dashboard.Task-label-validate;', array('author' => $task->getDescriptionAsHtml())),
					'label' => f_util_HtmlUtils::textToHtml($document->getPersistentModel()->isLocalized() ? $document->getLabelForLang($task->getLang()) : $document->getLabel()),
					'thread' => f_util_HtmlUtils::textToHtml($document->getDocumentService()->getPathOf($document)),
					'comment' => $task->getCommentaryAsHtml(),
					'author' => ucfirst($task->getDescriptionAsHtml()),
					'status' => ucfirst($status),
				    'locate' => "locateDocumentInModule(". $document->getId() . ", 'website');"
					);
				$taskAttr[] = $attr;
			}
			$request->setAttribute('tasks', $taskAttr);
		}
	}
}