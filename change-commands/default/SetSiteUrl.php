<?php
class commands_SetSiteUrl extends commands_AbstractChangeCommand
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
		return "ssu";
	}

	/**
	 * @return String
	 */
	function getDescription()
	{
		return "Set default site URL using declared domain in config";
	}

	/**
	 * @param String[] $params
	 * @param array<String, String> $options where the option array key is the option name, the potential option value or true
	 * @see c_ChangescriptCommand::parseArgs($args)
	 */
	function _execute($params, $options)
	{
		$this->message("== Set site URL ==");
		
		$this->loadFramework();
		RequestContext::getInstance()->setLang(RequestContext::getInstance()->getDefaultLang());
		$url = Framework::getConfiguration('general/server-fqdn');
		$tm = f_persistentdocument_TransactionManager::getInstance();
		try
		{
			$tm->beginTransaction();
			$website = website_WebsiteModuleService::getInstance()->getDefaultWebsite();
			$website->setDomain($url);
			// TODO: handle secure protocol
			$website->setProtocol('http');
			$website->setUrl('http://'.$url);
			$website->save();
			$tm->commit();
		}
		catch (Exception $e)
		{
			throw $tm->rollBack($e);
		}
		
		$this->quitOk("Default website URL is now http://$url");	
	}
}