<?php
class website_InitDefaultStructAction extends f_action_BaseJSONAction
{
	/**
	 * @param Context $context
	 * @param Request $request
	 */
	public function _execute($context, $request)
	{
		$website = $this->getDocumentInstanceFromRequest($request);
		try
		{
			if ($website instanceof website_persistentdocument_website)
			{
				$node = TreeService::getInstance()->getInstanceByDocument($website);
				if (count($node->getChildren('modules_website/topic')) == 0)
				{
					if (Framework::hasConfiguration('modules/website/structure/default'))
					{
						$scriptPath = f_util_FileUtils::buildWebeditPath(Framework::getConfiguration('modules/website/structure/default'));
					}
					else
					{
						$scriptPath = f_util_FileUtils::buildWebeditPath('modules', 'website', 'setup', 'sample.xml');
					}
					
					website_WebsiteService::getInstance()->initDefaultStruct($website, $scriptPath);
				}
				else
				{
					throw new BaseException('Website is not empty', 'modules.website.bo.actions.Website-is-not-empty');
				}
			}
			else
			{
				throw new BaseException('Invalid website', 'modules.website.bo.actions.Invalid-website');
			}
		}
		catch (Exception $e)
		{
			return $this->sendJSONException($e);
		}
		return $this->sendJSON(array('id' => $website->getId()));
	}
	
	/**
	 * @param string $scriptPath
	 * @param website_persistentdocument_website $website
	 */
	private function updateScript($scriptPath, $website)
	{
		$script = new DOMDocument('1.0', 'UTF-8');
		$script->load($scriptPath);
		
		$xmlWebsite = $script->getElementsByTagName('website')->item(0);
		$xmlWebsite->setAttribute('documentid', $website->getId());
		$xmlWebsite->setAttribute('domain', $website->getDomain());
		$xmlWebsite->setAttribute('url', $website->getUrl());
		
		$xmlWebsite->removeAttribute('label');
		$xmlWebsite->removeAttribute('label-en');
		$xmlWebsite->removeAttribute('protocol');
		$xmlWebsite->removeAttribute('localizebypath');
		$xmlWebsite->removeAttribute('byTag');
		
		$tmpFile = f_util_FileUtils::getTmpFile('Script_');
		$script->save($tmpFile);
		
		return $tmpFile;
	}
}