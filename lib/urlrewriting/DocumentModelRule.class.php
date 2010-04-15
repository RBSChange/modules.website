<?php
class website_lib_urlrewriting_DocumentModelRule
	extends website_lib_urlrewriting_Rule
{

	/**
	 * The document model name the rule is defined for.
	 *
	 * @var string
	 */
	private $documentModel = null;


	/**
	 * The view mode the rule is defined for ('list' or 'detail').
	 *
	 * @var string
	 */
	private $viewMode = null;


	/**
	 * Builds the rule object.
	 *
	 * @param string $package Package.
	 * @param string $template Template of the rule.
	 * @param string $documentModel Document model.
	 * @param string $viewMode The view mode, 'list' or 'detail'.
	 * @param array $parameters The parameters.
	 */
	public function __construct($package, $template, $documentModel, $viewMode, $parameters = null)
	{
		$this->documentModel = $documentModel;
		$this->viewMode = $viewMode;
		$this->initialize($package, $template, $parameters);
	}


	/**
	 * Returns the unique ID of the rule.
	 *
	 * @return string
	 */
	public function getUniqueId()
	{
		$id = $this->documentModel.' '.$this->viewMode;
		if ($this->m_lang)
		{
			$id .= ' '.$this->m_lang;
		}
		if ($this->m_condition)
		{
			$id .= ' '.$this->getCondition();
		}
		return $id;
	}


	/**
	 * Returns the document model name the rule is defined for.
	 *
	 * @return string
	 */
	public function getDocumentModelName()
	{
		return $this->documentModel;
	}


	/**
	 * Returns the view mode the rule is defined for.
	 *
	 * @return string
	 */
	public function getViewMode()
	{
		return $this->viewMode;
	}
	
	public function checkMatchRedirection($url)
	{
		if (isset($this->m_lastMatches['id']) && $this->viewMode == 'detail')
		{
			try 
			{
				$document = DocumentHelper::getDocumentInstance($this->m_lastMatches['id'], $this->documentModel);
				$lang = RequestContext::getInstance()->getLang();
				$currentURL = LinkHelper::getDocumentUrl($document, $lang);
				if (strpos($currentURL, $url) === false)
				{
					if (Framework::isInfoEnabled())
					{
						Framework::info(__METHOD__ . " Permanently redirect $url -> $currentURL");
					}
					if (f_util_ArrayUtils::isNotEmpty($_GET))
					{
						$currentURL .= "?" . http_build_query($_GET);
					}
					$this->setRedirectionUrl($currentURL);
					$this->setMovedPermanently(true);
					$this->m_lang = $lang;
				}
			}
			catch (Exception $e)
			{
				Framework::exception($e);
			}
		}
	}
}