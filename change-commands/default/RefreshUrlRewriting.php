<?php
class commands_RefreshUrlRewriting extends c_ChangescriptCommand
{
	/**
	 * @return String
	 */
	function getUsage()
	{
		return "";
	}
	
	function getAlias()
	{
		return "rurl";
	}

	/**
	 * @return String
	 */
	function getDescription()
	{
		return "Refresh auto calculated document URL";
	}

	/**
	 * @param String[] $params
	 * @param array<String, String> $options where the option array key is the option name, the potential option value or true
	 * @see c_ChangescriptCommand::parseArgs($args)
	 */
	function _execute($params, $options)
	{
		$this->message("== Refresh auto calculated document URL ==");
		
		$this->loadFramework();
		website_UrlRewritingService::getInstance()->refreshAllDocumentUrl(array(), true);
		$this->quitOk('Refresh successfully.');
	}
}