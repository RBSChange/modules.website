<?php
/**
 * website_MarkerService
 * @package website
 */
class website_MarkerService extends f_persistentdocument_DocumentService
{
	/**
	 * @var website_MarkerService
	 */
	private static $instance;
	
	/**
	 * @return website_MarkerService
	 */
	public static function getInstance()
	{
		if (self::$instance === null)
		{
			self::$instance = self::getServiceClassInstance(get_class());
		}
		return self::$instance;
	}
	
	/**
	 * @return website_persistentdocument_marker
	 */
	public function getNewDocumentInstance()
	{
		return $this->getNewDocumentInstanceByModelName('modules_website/marker');
	}
	
	/**
	 * Create a query based on 'modules_website/marker' model.
	 * Return document that are instance of modules_website/marker,
	 * including potential children.
	 * @return f_persistentdocument_criteria_Query
	 */
	public function createQuery()
	{
		return $this->pp->createQuery('modules_website/marker');
	}
	
	/**
	 * Create a query based on 'modules_website/marker' model.
	 * Only documents that are strictly instance of modules_website/marker
	 * (not children) will be retrieved
	 * @return f_persistentdocument_criteria_Query
	 */
	public function createStrictQuery()
	{
		return $this->pp->createQuery('modules_website/marker', false);
	}
	
	/**
	 * This function return the HTML to add the marker
	 * @param website_persistentdocument_website $website
	 * @return String
	 */
	public function getHtmlMarker($website)
	{
		$markers = $this->getByWebsiteAndLang($website, RequestContext::getInstance()->getLang());

		$html = '';
		foreach ($markers as $marker)
		{
			$model = $marker->getPersistentModel();
			try 
			{
				$templateLoader = TemplateLoader::getInstance()->setMimeContentType(K::HTML);
				$templateLoader->setPackageName('modules_' . $model->getModuleName());
				$template = $templateLoader->load(ucfirst($model->getModuleName()) . '-marker-Inc');
				$template->setAttribute('codeMarker', $marker->getAccount());
				$html .= $template->execute();
			}
			catch (TemplateNotFoundException $e)
			{
				Framework::info(__METHOD__ . $e->getMessage());
			}
		}		
		return $html;
	}
	
	/**
	 * Return the list of type of markers
	 * @return String[]
	 */
	public function getMarkerTypeList()
	{
		$markers = array();
		foreach (ModuleService::getInstance()->getModules() as $module)
		{
			if (f_util_StringUtils::beginsWith($module, 'modules_marker'))
			{
				$markers[] = substr($module, 14);
			}
		}
		return $markers;
	}	
	
	/**
	 * @param website_persistentdocument_website $website
	 * @param String $lang
	 * @return website_persistentdocument_marker[]
	 */
	public function getByWebsiteAndLang($website, $lang)
	{
		if ($website->isNew())
		{
			return array();
		}
		return website_MarkerService::getInstance()
			->createQuery()
			->add(Restrictions::published())
			->add(Restrictions::like('langs', $lang, MatchMode::ANYWHERE()))
			->add(Restrictions::descendentOf($website->getId()))
			->find();
	}
	
	/**
	 * @param website_persistentdocument_marker $marker
	 * @return website_persistentdocument_website
	 */
	public function getWebsiteByMarker($marker)
	{
		return website_WebsiteService::getInstance()->createQuery()->add(Restrictions::ancestorOf($marker->getId()))->findUnique();
	}
}