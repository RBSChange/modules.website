<?php
/**
 * @deprecated
 */
class website_MarkerfolderService extends generic_FolderService
{
	/**
	 * @deprecated
	 */
	private static $instance;

	/**
	 * @deprecated
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
	 * @deprecated
	 */
	public function getNewDocumentInstance()
	{
		return $this->getNewDocumentInstanceByModelName('modules_website/markerfolder');
	}

	/**
	 * @deprecated
	 */
	public function createQuery()
	{
		return $this->pp->createQuery('modules_website/markerfolder');
	}
	
	/**
	 * @deprecated
	 */
	public function createStrictQuery()
	{
		return $this->pp->createQuery('modules_website/markerfolder', false);
	}
}