<?php

class website_Crawler
{
	const HREF_REGEXP = '/<a ([^>]*)href="([^"]*)"/';
	const URL_REGEXP = '/http[s]{0,1}:\/\/.*\s/';
	
	/**
	 * @var String
	 */
	private $baseUrl;
	
	/**
	 * @var String
	 */
	private $baseUrlLength;
	
	/**
	 * @var Array
	 */
	private $websiteInfo;
	
	private $langRegexp;
	
	private $visitedUrls = array();
	
	private $visitedPageIds = array();
	
	/**
	 * @param website_persistentdocument_website $website
	 * @param String $lang
	 */
	public function crawl($website, $lang = null)
	{
		$rc = RequestContext::getInstance();
		if ($lang == null)
		{
			$lang = $rc->getLang();
		}
	
		try 
		{
			$rc->beginI18nWork($lang);
			$this->setWebsite($website);
			$indexPage = $website->getIndexPage();
			if ($indexPage)
			{
				$this->visitedPageIds[] = $indexPage->getId();
				$this->visitUrl($this->getStartUrl());
			}
			$rc->endI18nWork();
		}
		catch (Exception $e)
		{
			$rc->endI18nWork($e);
		}
		
	}
	
	/**
	 * @return String
	 */
	private function getStartUrl()
	{
		return $this->baseUrl;
	}
	
	/**
	 * @param website_persistentdocument_website $website
	 */
	private function setWebsite($website)
	{
		$wms = website_WebsiteModuleService::getInstance();
		$wms->setCurrentWebsite($website);
		$rawUrl = $website->getUrl();
		$this->websiteInfo = $wms->getWebsiteInfos(substr($rawUrl, 7));
		if ($this->websiteInfo['localizebypath'])
		{
			$this->baseUrl = $rawUrl . '/' . RequestContext::getInstance()->getLang();
			$this->langRegexp = '/^\/('. implode('|', $this->websiteInfo['langs']) .')\//';
		}
		else
		{
			$this->baseUrl = $rawUrl;
		}
		$this->baseUrlLength = strlen($rawUrl);
	}
	
	/**
	 * @param String $url
	 * @return String
	 */
	private function getRelativeUrlFromUrl($url)
	{
		if (strlen($url) <= $this->baseUrlLength)
		{
			return null;
		}
		$relativeUrl = substr($url, $this->baseUrlLength); 
		
		if ($this->websiteInfo['localizebypath'])
		{
			$matches = array();
			if (preg_match($this->langRegexp, $relativeUrl, $matches))
			{
			    $relativeUrl = str_replace($matches[0], '/', $relativeUrl);
			}
			
		}
		return $relativeUrl;
	}
	
	/**
	 * @param String $url
	 */
	private function visitUrl($url)
	{
		echo "$url\n";
		$this->visitedUrls[] = $url;
		$request = new website_CrawlerHTTPRequest($url);
		$data = $request->execute($url);
		$matches = array();
		if (preg_match('/var pageHandler = {id: (\d+),/', $data, $matches))
		{
			$pageId = intval($matches[1]);			
			$this->visitedPageIds[$pageId] = $pageId;
		}
		
		$links = $this->getHrefArrayFromData($data);
		$request = null;
		foreach ($links as $followUrl)
		{
			if (!in_array($followUrl, $this->visitedUrls))
			{
				$this->visitUrl($followUrl);
			}
		}
		$links = null;
	}
	
	/**
	 * @param String $content
	 * @return String[]
	 */
	private function getHrefArrayFromData($content)
	{
		$matches = array();
		$result = array();
		preg_match_all(self::HREF_REGEXP, $content, $matches);
		foreach ($matches[2] as $match)
		{
			if (strpos($match, 'publicmedia') == false && strpos($match, $this->baseUrl) !== false && !in_array($match, $this->visitedUrls))
			{
				$result[] = $match;
			}
		}
		return $result;
	}
	
	public function getVisitedPageIds()
	{
		return $this->visitedPageIds;
	}
}