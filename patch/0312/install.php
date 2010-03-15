<?php
/**
 * website_patch_0312
 * @package modules.website
 */
class website_patch_0312 extends patch_BasePatch
{
    /**
     * Returns true if the patch modify code that is versionned.
     * If your patch modify code that is versionned AND database structure or content,
     * you must split it into two different patches.
     * @return Boolean true if the patch modify code that is versionned.
     */
	public function isCodePatch()
	{
		return true;
	}
 
	/**
	 * Entry point of the patch execution.
	 */
	public function execute()
	{
		$oldthreadTemplate = f_util_FileUtils::buildWebappPath('modules', 'website', 'templates', 'Website-Block-Thread-Success.all.all.html');
		if (file_exists($oldthreadTemplate))
		{
			$newPath = str_replace('Thread-Success', 'ThreadOld-Success', $oldthreadTemplate);
			$this->log('Template renamed : ' . $oldthreadTemplate);
			rename($oldthreadTemplate, $newPath);
			$templatePath = dirname($newPath);
			$files = scandir($templatePath);

			foreach ($files as $fileName) 
			{
				if (strpos($fileName, '.all.all.xul'))
				{
					$filePath = f_util_FileUtils::buildAbsolutePath($templatePath, $fileName);
					$content = file_get_contents($filePath);
					if (strpos($content, 'type="modules_website_thread"'))
					{
						$newContent = str_replace('type="modules_website_thread"', 'type="modules_website_threadOld"', $content);
						file_put_contents($filePath, $newContent);
						$this->log('Template updated : ' . $filePath);
					}
				}
			}
		}
		else
		{
			$this->log('No update needed.');
		}
	}

	/**
	 * @return String
	 */
	protected final function getModuleName()
	{
		return 'website';
	}

	/**
	 * @return String
	 */
	protected final function getNumber()
	{
		return '0312';
	}
}