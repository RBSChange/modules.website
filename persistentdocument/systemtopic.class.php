<?php
/**
 * website_persistentdocument_systemtopic
 * @package modules.website
 */
class website_persistentdocument_systemtopic extends website_persistentdocument_systemtopicbase
{
	/**
	 * @return f_persistentdocument_PersistentDocument
	 */
	public function getReference()
	{
		if ($this->getReferenceId())
		{
			return DocumentHelper::getDocumentInstance($this->getReferenceId());
		}
		return null;
	}
	
	public function getReferenceURI()
	{
		$ref = $this->getReference();
		if ($ref)
		{
			$model = $ref->getPersistentModel();
			$type = str_replace('/', '_', $model->getName());
			$uri = array($model->getModuleName(), 'openDocument', $type, $ref->getId(), 'properties');
			return implode(',', $uri);
		}
		return null;
	}
}