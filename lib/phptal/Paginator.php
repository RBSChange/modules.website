<?php
class PHPTAL_Php_Attribute_CHANGE_Paginator extends ChangeTalAttribute 
{
	/**
	 * @see ChangeTalAttribute::evaluateAll()
	 *
	 * @return Boolean
	 */
	protected function evaluateAll()
	{
		return true;
	}
	
	/**
	 * @see ChangeTalAttribute::getDefaultParameterName()
	 *
	 * @return String
	 */
	protected function getDefaultParameterName()
	{
		return 'paginator';
	}
	
	public static function renderPaginator($params)
	{
		if (!isset($params['paginator']))
		{
			Framework::warn(__METHOD__ . ": No paginator instance found...skipping rendering");
			return;
		}
		
		$paginator = $params['paginator'];
		if (!$paginator instanceof paginator_Paginator)
		{
			Framework::warn(__METHOD__ . ": No paginator instance found...skipping rendering");
			return;
		}
		
		if (isset($params['module']))
		{
			$paginator->setTemplateModuleName($params['module']);
		}
		
		if (isset($params['template']))
		{
			$paginator->setTemplateFileName($params['template']);
		}
		echo $paginator->execute();
	}
}