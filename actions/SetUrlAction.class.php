<?php
class website_SetUrlAction extends website_Action
{

	public function _execute($context, $request)
	{
		$pageUrls = $request->getParameter('url');

		$errors = array();

		// For each page references we have, set the URL
		foreach ($pageUrls as $pageRefId => $pageUrl)
		{
			try
			{
				$pageRef->setUrl($pageUrl);
			}
			catch (ClassException $e)
			{
				$errors[] = $e->getLocaleMessage();
			}
		}

		if ( ! empty($errors) )
		{
			$request->setAttribute('message', join("\n", $errors));
			return View::ERROR;
		}

		return View::SUCCESS;
	}
}