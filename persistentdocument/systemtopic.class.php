<?php
/**
 * website_persistentdocument_systemtopic
 * @package modules.website
 */
class website_persistentdocument_systemtopic extends website_persistentdocument_systemtopicbase
{
	/**
	 * @deprecated use getReferenceIdInstance
	 */
	public function getReference()
	{
		return $this->getReferenceIdInstance();
	}
	
	public function getReferenceURI()
	{
		$ref = $this->getReferenceIdInstance();
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