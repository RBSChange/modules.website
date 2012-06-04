<?php
/**
 * @package modules.website
 */
class website_CleanCSSAndJSCacheTask extends task_SimpleSystemTask
{
	/**
	 * @see task_SimpleSystemTask::execute()
	 */
	protected function execute()
	{
		$cssDir = f_util_FileUtils::buildWebCachePath("css");
		if (is_dir($cssDir))
		{
			$di = new RecursiveDirectoryIterator($cssDir, RecursiveDirectoryIterator::KEY_AS_PATHNAME);
			$fi = new website_DeteledAndDirFilterIterator($di);
			$it = new RecursiveIteratorIterator($fi, RecursiveIteratorIterator::CHILD_FIRST);
			
			foreach ($it as $file => $info)
			{
				if ($info->isFile())
				{
					$f = substr($file, 0, strlen($file) - 8);
					@unlink($f);
					@unlink($file);
				}
			}
		}
		
		$this->plannedTask->ping();

		$jsDir = f_util_FileUtils::buildWebCachePath("js");
		if (is_dir($jsDir))
		{
			$di = new RecursiveDirectoryIterator($jsDir, RecursiveDirectoryIterator::KEY_AS_PATHNAME);
			$fi = new website_DeteledAndDirFilterIterator($di);
			$it = new RecursiveIteratorIterator($fi, RecursiveIteratorIterator::CHILD_FIRST);

			foreach ($it as $file => $info)
			{
				if ($info->isFile())
				{
					$f = substr($file, 0, strlen($file) - 8);
					@unlink($f);
					@unlink($file);
				}
			}
		}
	
	}
}

class website_DeteledAndDirFilterIterator extends RecursiveFilterIterator 
{
	public function accept() 
	{
		$c = $this->current();
		if ($c->isDir() || ($c->isFile() && substr($c->getFilename(), -8) == '.deleted'))
		{
			return true;
		}
		return false;
	}
}