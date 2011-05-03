<?php
/**
 * website_patch_0353
 * @package modules.website
 */
class website_patch_0353 extends patch_BasePatch
{ 
	/**
	 * Entry point of the patch execution.
	 */
	public function execute()
	{
		$paths = glob(f_util_FileUtils::buildChangeBuildPath('modules', '*', 'blocks'), GLOB_ONLYDIR);
		if (f_util_ArrayUtils::isNotEmpty($paths))
		{
			foreach ($paths as $path)
			{
				$this->log('Clean : ' . $path);
				$files = scandir($path);
				foreach ($files as $file) 
				{
					if ($file !== '.' && $file !== '..')
					{
						unlink($path  . DIRECTORY_SEPARATOR . $file);
					}
				}
				rmdir($path);
			}
		}
		$this->log('compile-blocks ...');
		$this->execChangeCommand('compile-blocks');
	}
}