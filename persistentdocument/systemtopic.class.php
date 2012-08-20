<?php
/**
 * website_persistentdocument_systemtopic
 * @package modules.website
 */
class website_persistentdocument_systemtopic extends website_persistentdocument_systemtopicbase
{
	/**
	 * @return f_persistentdocument_PersistentDocument|null
	 */
	public function getReference()
	{
		return DocumentHelper::getDocumentInstanceIfExists($this->getReferenceId());
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