<?php
class formatter_Xhtml
{
	public function format($xhtml)
	{
		$xml = '<?xml version="1.0" encoding="UTF-8"?><!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "file://'. WEBEDIT_HOME .'/modules/website/lib/fckeditor/xhtml1DTD/xhtml1-transitional.dtd"><xhtml xmlns="http://www.w3.org/1999/xhtml" xmlns:change="http://www.rbs.fr/change/1.0/schema"><richtextcontent>' . $xhtml .'</richtextcontent></xhtml>';
		$doc = new DOMDocument();
		$doc->substituteEntities = false;
		$doc->resolveExternals = true;
		$doc->loadXML($xml);
		$contentElement = $doc->getElementsByTagName('richtextcontent')->item(0);
		if ($contentElement === null) {return null;}
		
		
		$images = $contentElement->getElementsByTagName('img');
		foreach ($images as $image) 
		{
			$src = $image->getAttribute('src');
			$this->encodeElement($image, $src, 'src');	
		}
		
		$links = $contentElement->getElementsByTagName('a');
		foreach ($links as $link) 
		{
			$href = $link->getAttribute('href');
			$this->encodeElement($link, $href, 'href');	
		}
		
		return str_replace(array('<richtextcontent>', '</richtextcontent>'), '', $doc->saveXML($contentElement));
	}
	
	private function encodeElement($element, $url, $urlAttributeName)
	{
		if (empty($url)) {return;}
		$path = $this->getCurrentChangePath($url);
		if ($path === null) {return;}
		
		$element->setAttribute($urlAttributeName, $path);
		
		$params = $this->getMediaParams($path);
		if ($params !== null)
		{
			list($id, $lang) = $params;
			$element->setAttribute('cmpref', $id);
			$element->setAttribute('lang', $lang);
			return;
		}		
		
		$params = $this->getRewritedUrlParams($path);
		if ($params !== null)
		{
			if (isset($params['id']))
			{
				$element->setAttribute('cmpref', $params['id']);
			}
			else
			{
				foreach ($params as $key => $value) 
				{
					$element->setAttribute($key, $value);
				}
			}
			return;
		}		
	}
	
	private function getLangsPattern()
	{
		return implode('|', RequestContext::getInstance()->getSupportedLanguages());
	}

	private function getCurrentChangePath($url)
	{
		$match = array();
		if (preg_match('/^https?:\/\/(.*?)(\/.*)$/', $url, $match))
		{
			$domaine = $match[1];
			if (website_WebsiteModuleService::getInstance()->getWebsiteByUrl($domaine) !== null)
			{
				return $match[2];
			}
		} 
		else if (preg_match('/^(?:\.\.\/)+(.*)$/', $url, $match))
		{
			return '/' . $match[1];
		}
		return null;
	}
	
	private function getMediaParams($path)
	{
		$match = array();
		if (preg_match('/^\/publicmedia\/(original|formatted)\/((?:[0-9]+\/)+)('.$this->getLangsPattern().')\/(.*)$/', $path, $match))
		{
			$id = intval(str_replace('/', '', $match[2]));
			$lang = $match[3];
			if ($id > 0)
			{
				return array($id, $lang);
			}
		}
		return null;
	}
	
	private function getRewritedUrlParams($path)
	{
		$match = array();
		if (preg_match('/^(\/'.$this->getLangsPattern().'){0,1}(\/.*)$/', $path, $match))
		{
			$url = $match[2];
			$rule = website_UrlRewritingService::getInstance()->getRuleByUrl($url);
			if ($rule instanceof website_lib_urlrewriting_DocumentModelRule) 
			{
				return $rule->getMatches();
			}
			else if ($rule instanceof website_lib_urlrewriting_TaggedPageRule)
			{
				return array('tag' => $rule->getPageTag());
			}
			else if ($rule instanceof website_lib_urlrewriting_ModuleActionRule)
			{
				return array('module' => $rule->getModule(), 'module' => $rule->getAction());
			}
		}
		return null;		
	}
}