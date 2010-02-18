<?php
/**
 * website_patch_0303
 * @package modules.website
 */
class website_patch_0303 extends patch_BasePatch
{
	/**
	 * Entry point of the patch execution.
	 */
	public function execute()
	{
		parent::execute();
		
		// Update topic structure.
		try 
		{
			$this->executeSQLQuery("ALTER TABLE `m_website_doc_topic` ADD `referenceid` int(11);");
		}
		catch (Exception $e)
		{
			if (strpos($e->getMessage(), '42S21') !== false)
			{
				$this->logWarning('Database already patched.');
			}
			else
			{
				throw $e;
			}
		}
	}

	/**
	 * Returns the name of the module the patch belongs to.
	 *
	 * @return String
	 */
	protected final function getModuleName()
	{
		return 'website';
	}

	/**
	 * Returns the number of the current patch.
	 * @return String
	 */
	protected final function getNumber()
	{
		return '0303';
	}
}