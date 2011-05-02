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
		$html = array();
		foreach ($markers as $marker)
		{
			$html[] = $marker->getDocumentService()->getHtmlBody($marker);
		}		
		return implode(K::CRLF, $html);
	}
		
	/**
	 * @param website_persistentdocument_marker $marker
	 * @return string
	 */
	public function getHtmlBody($marker)
	{
		$model = $marker->getPersistentModel();
		try 
		{
			$templateLoader = TemplateLoader::getInstance()->setMimeContentType(K::HTML);
			$templateLoader->setPackageName('modules_' . $model->getModuleName());
			$template = $templateLoader->load(ucfirst($model->getModuleName()) . '-marker-Inc');
			$template->setAttribute('codeMarker', $marker->getAccount());
			$template->setAttribute('marker', $marker);
			return $template->execute();
		}
		catch (TemplateNotFoundException $e)
		{
			Framework::info(__METHOD__ . $e->getMessage());
		}		
		return '';
	}
	
	/**
	 * @param website_persistentdocument_marker $marker
	 * @return string
	 */
	public function getHtmlHead($marker)
	{
		$model = $marker->getPersistentModel();
		try 
		{
			$templateLoader = TemplateLoader::getInstance()->setMimeContentType(K::HTML);
			$templateLoader->setPackageName('modules_' . $model->getModuleName());
			$template = $templateLoader->load(ucfirst($model->getModuleName()) . '-marker-IncHead');
			$template->setAttribute('codeMarker', $marker->getAccount());
			$template->setAttribute('marker', $marker);
			return $template->execute();
		}
		catch (TemplateNotFoundException $e)
		{
			Framework::info(__METHOD__ . $e->getMessage());
		}		
		return '';
	}
	
	/**
	 * Return the list of type of markers
	 * @return String[]
	 */
	public function getMarkerTypeList()
	{
		$markerModel = f_persistentdocument_PersistentDocumentModel::getInstance('website', 'marker');
		return $markerModel->getChildrenNames();
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
			->add(Restrictions::eq('website', $website))
			->find();
	}
	
	/**
	 * @param website_persistentdocument_website $website
	 * @return website_persistentdocument_marker[]
	 */
	public function getAllByWebsite($website)
	{
		if ($website->isNew())
		{
			return array();
		}
		return website_MarkerService::getInstance()->createQuery()
			->add(Restrictions::eq('website', $website))
			->find();
	}
	
	/**
	 * @param website_persistentdocument_marker $marker
	 * @return website_persistentdocument_website
	 */
	public function getWebsiteByMarker($marker)
	{
		if ($marker instanceof website_persistentdocument_marker)
		{
			return $marker->getWebsite();
		}
		return null;
	}
	
	/**
	 * @param website_persistentdocument_marker $marker
	 */
	public function getMarkerGridInfo($marker)
	{
		$model = $marker->getPersistentModel();
		if ($marker->isPublished())
		{
			$statusSrc = MediaHelper::getIcon('published-document', MediaHelper::SMALL);
		}
		else
		{
			$statusSrc = MediaHelper::getIcon('publishable-document', MediaHelper::SMALL);
		}
		return array(
			'id' => $marker->getId(),
			'type' =>  implode('_', array('modules', $model->getModuleName(), $model->getDocumentName())),
			'model' => $marker->getDocumentModelName(),
			'status' => $statusSrc,
			'websiteid' => $marker->getWebsite() ? $marker->getWebsite()->getId() : null,
		    'typename' => LocaleService::getInstance()->transBO('m.' . $model->getModuleName() . '.bo.general.markertype', array('ucf')),
			'label' => $marker->getLabel(),
			'account' => $marker->getAccount(),
			'langs' => str_replace(',', ', ', $marker->getLangs())
		);
	}
	
	/**
	 * @param string $documentModelName
	 * @param string $account
	 * @param website_persistentdocument_website $website
	 * @return website_persistentdocument_marker
	 */
	public function createNewMarker($documentModelName, $account, $website)
	{
		$modelsName = $this->getMarkerTypeList();
		$marker = null;
		if (in_array($documentModelName, $modelsName))
		{
			$service = self::getInstanceByDocumentModelName($documentModelName);
			if ($service instanceof website_MarkerService)
			{
				$rc = RequestContext::getInstance();
				try 
				{
					$rc->beginI18nWork($website->getLang());
					$marker = $service->getNewDocumentInstance();
					$marker->setAccount($account);
					$marker->setLabel($account);
					$marker->setWebsite($website);
					$marker->setLangs(implode(',', $website->getI18nInfo()->getLangs()));
					$service->save($marker);
					$rc->endI18nWork();
				} 
				catch (Exception $e) 
				{
					$rc->endI18nWork($e);
				}
			}
		}
		else
		{
			Framework::warn('Invalid marker model name: ' . $documentModelName);
		}
		return $marker;
	}
	
	
	/**
	 * @param website_persistentdocument_marker $document
	 * @param string $actionType
	 * @param array $formProperties
	 */
	public function addFormProperties($document, $propertiesNames, &$formProperties)
	{
		$formProperties['websiteid'] = ($document->getWebsite()) ? $document->getWebsite()->getId() : null;
	}
}