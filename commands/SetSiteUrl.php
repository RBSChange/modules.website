<?php
class commands_SetSiteUrl extends c_ChangescriptCommand
{
	/**
	 * @return string
	 */
	function getUsage()
	{
		return "";
	}
	
	function getAlias()
	{
		return "ssu";
	}

	/**
	 * @return string
	 */
	function getDescription()
	{
		return "Set default site URL using declared domain in config";
	}

	/**
	 * @param string[] $params
	 * @param array<String, String> $options where the option array key is the option name, the potential option value or true
	 * @see c_ChangescriptCommand::parseArgs($args)
	 */
	function _execute($params, $options)
	{
		$this->message("== Set site URL ==");
		
		$this->loadFramework();
		RequestContext::getInstance()->setLang(RequestContext::getInstance()->getDefaultLang());
		$tm = f_persistentdocument_TransactionManager::getInstance();
		try
		{
			$tm->beginTransaction();
			$website = website_WebsiteService::getInstance()->getDefaultWebsite();
			$website->setDomain(DEFAULT_HOST);
			// TODO: handle secure protocol
			$website->setProtocol('http');
			$website->setUrl('http://'. DEFAULT_HOST);
			$website->save();
			$tm->commit();
		}
		catch (Exception $e)
		{
			throw $tm->rollBack($e);
		}
		
		$this->quitOk("Default website URL is now http://" . DEFAULT_HOST);	
	}
}