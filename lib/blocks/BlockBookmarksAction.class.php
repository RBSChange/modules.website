<?php
class website_BlockBookmarksAction extends website_BlockAction
{
	/**
	 * @see f_mvc_Action::execute()
	 *
	 * @param f_mvc_Request $request
	 * @param f_mvc_Response $response
	 * @return string
	 */
	function execute($request, $response)
	{
		if ($this->isInBackofficeEdition())
		{
			return website_BlockView::NONE;
		}
		
		$input = $this->findParameterValue(change_Request::DOCUMENT_ID);
		if (is_array($input))
		{
			$bookmarks_in = explode(';', implode('', $input));
		}
		else if (is_string($input))
		{
			$bookmarks_in =  explode(';', $input);
		}
		else
		{
			$bookmarks_in = array();
		}
		
		$bookmarks_out = array();

		foreach ($bookmarks_in as $bookmark_in)
		{
			$bookmark_out = explode(':', $bookmark_in);

			if (count($bookmark_out) == 2)
			{
				$bookmarks_out[] = array(
				   'href' => '#' . trim(urldecode($bookmark_out[0])),
				   'content' => trim(urldecode($bookmark_out[1]))
				);
			}
		}

		$request->setAttribute('bookmarks', $bookmarks_out);

		if (empty($bookmarks_out))
		{
			return website_BlockView::NONE;
		}

		return website_BlockView::SUCCESS;
	}
}