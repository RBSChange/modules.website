<?php
/**
 * @package modules.website
 * @method website_RewriteruleService getInstance()
 */
class website_RewriteruleService extends f_persistentdocument_DocumentService
{
	/**
	 * @return website_persistentdocument_rewriterule
	 */
	public function getNewDocumentInstance()
	{
		return $this->getNewDocumentInstanceByModelName('modules_website/rewriterule');
	}

	/**
	 * Create a query based on 'modules_website/rewriterule' model.
	 * Return document that are instance of modules_website/rewriterule,
	 * including potential children.
	 * @return f_persistentdocument_criteria_Query
	 */
	public function createQuery()
	{
		return $this->getPersistentProvider()->createQuery('modules_website/rewriterule');
	}
	
	/**
	 * Create a query based on 'modules_website/rewriterule' model.
	 * Only documents that are strictly instance of modules_website/rewriterule
	 * (not children) will be retrieved
	 * @return f_persistentdocument_criteria_Query
	 */
	public function createStrictQuery()
	{
		return $this->getPersistentProvider()->createQuery('modules_website/rewriterule', false);
	}
	
	/**
	 * @param string $modelName
	 * @return website_persistentdocument_rewriterule
	 */
	public function getByModelName($modelName)
	{
		return $this->createQuery()->add(Restrictions::eq('modelName', $modelName))->findUnique();
	}
	
	/**
	 * @return website_persistentdocument_rewriterule[] 
	 */
	public function getPublishedDocumentRules()
	{
		return $this->createQuery()
			->add(Restrictions::isNotNull('modelName'))
			->add(Restrictions::published())
			->find();
	}
	
	/**
	 * @param string $moduleName
	 * @param string $actionName
	 * @return website_persistentdocument_rewriterule
	 */
	public function getByModuleActionName($moduleName, $actionName)
	{
		return $this->createQuery()->add(Restrictions::eq('moduleName', $moduleName))->add(Restrictions::eq('actionName', $actionName))
			->findUnique();
	}
	

	
	/**
	 * @param XMLElement $definition
	 * @param boolean $override
	 */
	public function addDefinition($ruleData, $override = false)
	{
		try 
		{
			$this->getTransactionManager()->beginTransaction();
			if (isset($ruleData['model']))
			{
				$documentModel = $ruleData['model'];
				$rule = $this->getByModelName($documentModel);
				if ($rule === null)
				{
					$rule = $this->getNewDocumentInstance();
					$rule->setModelName($documentModel);
					$rule->setRuleData($ruleData);
				}
				else
				{
					$defaultRuleData = 	$rule->getRuleData();
					$setDef = false;
					foreach ($ruleData['parameters'] as $pn => $pnInfo) 
					{
						if (!isset($defaultRuleData['parameters'][$pn]))
						{
							$setDef = true;
							$defaultRuleData['parameters'][$pn] = $pnInfo;
						}
					}
					if ($setDef)
					{
						unset($defaultRuleData['id']);
						$rule->setRuleData($defaultRuleData);
					}
				}
				$label = 'Document rule: ' . $documentModel;
			}
			else
			{
				$moduleName = $ruleData['module'];
				$actionName = $ruleData['action'];
				$rule = $this->getByModuleActionName($moduleName, $actionName);
				if ($rule === null)
				{
					$rule = $this->getNewDocumentInstance();
				}
				$label = 'Action rule: ' . $moduleName . '/' . $actionName;
				$rule->setRuleData($ruleData);
			}
			$rule->setModuleName($ruleData['module']);
			$rule->setActionName($ruleData['action']);
			$rc = RequestContext::getInstance();
			foreach ($rc->getSupportedLanguages() as $lang) 
			{				
				try 
				{
					$rc->beginI18nWork($lang);
					if ($override || $rule->getTemplate() == null)
					{
						$template  = isset($ruleData['lang'][$lang]) ? $ruleData['lang'][$lang] : f_util_ArrayUtils::firstElement($ruleData['lang']);
						$rule->setTemplate($template);
					}
					$rule->setModificationdate(null);
					$rule->setLabel($label);
					$this->save($rule);
					$rc->endI18nWork();
				} 
				catch (Exception $e) 
				{
					$rc->endI18nWork($e);
				}
			}
			$this->getTransactionManager()->commit();
		}
		catch (Exception $e)
		{
			$this->getTransactionManager()->rollBack($e);
		}
	}
		
	/**
	 * @param website_persistentdocument_rewriterule $document
	 * @param integer $parentNodeId Parent node ID where to save the document (optionnal => can be null !).
	 * @return void
	 */
	protected function preSave($document, $parentNodeId)
	{
		if ($document->getLabel() == null)
		{
			if ($document->getModelName() != null)
			{
				$document->setLabel('Document rule: ' . $document->getModelName());
			}
			else
			{
				$document->setLabel('Action rule: ' . $document->getModuleName() . '/' . $document->getActionName());
			}
		}
		
		if ($document->getModelName() != null && $document->getModuleName() == null)
		{
			$infos = f_persistentdocument_PersistentDocumentModel::getModelInfo($document->getModelName());
			$document->setModuleName($infos['module']);
		}
		
		if ($document->getModelName() != null && $document->getActionName() == null)
		{
			$document->setActionName('ViewDetail');
		}
		
		if ($document->getTemplate() == null)
		{
			if ($document->getModelName() != null)
			{
				$infos = f_persistentdocument_PersistentDocumentModel::getModelInfo($document->getModelName());
				$document->setTemplate('/rewrite/'.$infos['module'].'/${id}/${label}.html');
			}
		}
	}
	
	/**
	 * @param website_persistentdocument_rewriterule $document
	 * @param integer $parentNodeId Parent node ID where to save the document (optionnal).
	 * @return void
	 */
	protected function preUpdate($document, $parentNodeId)
	{
		if ($document->isPropertyModified('template') && !$document->isPropertyModified('definition'))
		{
			$template = $document->getTemplate();
			if (empty($template)) 
			{
				$infos = f_persistentdocument_PersistentDocumentModel::getModelInfo($document->getModelName());
				$template = '/rewrite/'.$infos['module'].'/${id}/${label}.html';
				$document->setTemplate($template);
			} 
			else if ($template[0] != '/')
			{
				$template = '/' . $template;
				$document->setTemplate($template);
			}
				
			if ($document->getDefinition() != null)
			{
				$lang = RequestContext::getInstance()->getLang();
				$ruleData = $document->getRuleData();
				if ($ruleData['lang'][$lang] != $template)
				{	
					$ruleData['lang'][$lang] = $template;
					$document->setRuleData($ruleData);
				}
			}
		}
	}

	/**
	 * @param website_persistentdocument_rewriterule $rule
	 */
	public function generateRuleData($rule)
	{
		$ruleData = array('id' => $rule->getId(), 
						  'module' => $rule->getModuleName(), 
						  'action' => $rule->getActionName(), 
						  'parameters' => array(),
						  'lang' => array());
				
		foreach (RequestContext::getInstance()->getSupportedLanguages() as $lang) 
		{
			if ($rule->isLangAvailable($lang))
			{
				$ruleData['lang'][$lang] = $rule->getTemplateForlang($lang);
			}
			else
			{
				$ruleData['lang'][$lang] = $rule->getVoTemplate();
			}
		}
		
		if ($rule->getModelName())
		{
			$ruleData['model'] = $rule->getModelName();
			$document = f_persistentdocument_DocumentService::getInstanceByDocumentModelName($ruleData['model'])->getNewDocumentInstance();
			foreach ($ruleData['lang'] as  $pathTemplate) 
			{
				$matches = array();
				if (preg_match_all('/\$\{([a-zA-Z0-9]+)\}/', $pathTemplate, $matches, PREG_SET_ORDER))
				{
					foreach ($matches as $match) 
					{
						$pn = $match[1];
						if (!isset($ruleData['parameters'][$pn]))
						{
							$method = 'get' . ucfirst($pn);
							if (f_util_ClassUtils::methodExists($document, $method))
							{
								$ruleData['parameters'][$pn] = array('type' => 'out', 'method' => $method);
							}
						}
					}
				}
			}
		}
		return $ruleData;
	}

	/**
	 * @param website_persistentdocument_rewriterule $document
	 * @param string $forModuleName
	 * @param array $allowedSections
	 * @return array
	 */
	public function getResume($document, $forModuleName, $allowedSections = null)
	{
		$resume = parent::getResume($document, $forModuleName, $allowedSections);
		$resume['properties']['compile'] = '...';
		return $resume;
	}
	
	/**
	 * @param website_persistentdocument_rewriterule $document
	 * @param string[] $propertiesName
	 * @param Array $datas
	 */
	public function addFormProperties($document, $propertiesName, &$datas)
	{
		if (in_array('template', $propertiesName) && $document->getModelName())
		{
			$vars = array();
			$ruleData = $document->getRuleData();
			foreach ($ruleData['parameters'] as $name => $value) 
			{
				$vars[$name] = array('label' => $name, 'value' => '${' . $name . '}');
			}
			
			$model = f_persistentdocument_PersistentDocumentModel::getInstanceFromDocumentModelName($document->getModelName());	 
			foreach (array_reverse($model->getEditablePropertiesInfos())  as $property) 
			{
				if ($property instanceof PropertyInfo) 
				{
					$name = $property->getName();
					switch ($property->getType()) 
					{
						case f_persistentdocument_PersistentDocument::PROPERTYTYPE_STRING:
						case f_persistentdocument_PersistentDocument::PROPERTYTYPE_INTEGER:
							$vars[$name] = array('label' => $name, 'value' => '${' . $name . '}');
							break;
					}
				}
			}
			$datas['templatevars'] = array_values($vars);
		}
	}
}