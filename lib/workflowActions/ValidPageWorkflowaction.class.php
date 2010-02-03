<?php
class website_ValidPageWorkflowaction extends workflow_BaseWorkflowaction
{
	/**
	 * This method will execute the action.
	 * @return boolean true if the execution end successfully, false in error case.
	 */
	function execute()
	{
		$decision = $this->getDecision();
		if ($decision)
		{
			$this->setExecutionStatus($decision);
			return true;
		}
		else
		{
			return false;
		}
	}
	
	/**
	 * @see workflow_BaseWorkflowaction::updateTaskInfos()
	 *
	 * @param task_persistentdocument_usertask $task
	 */
	public function updateTaskInfos($task)
	{
		$commentary = $this->getCaseParameter('START_COMMENT');
		
		$author = $this->getCaseParameter('workflowAuthor');
		if (!empty($author))
		{
			$task->setDescription($author);
		}
		if (!empty($commentary))
		{
			$task->setCommentary($commentary);
		}	
	}
}