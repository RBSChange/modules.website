<?php
/**
 * website_BlockIframeAction
 * @package modules.website.lib.blocks
 */
class website_BlockIframeAction extends website_BlockAction
{
	/**
	 * @param f_mvc_Request $request
	 * @param f_mvc_Response $response
	 * @return string
	 */
	public function execute($request, $response)
	{
		$configuration = $this->getConfiguration();
		
		$width = $configuration->getFrameWidth();
		if (is_integer($width)) // For compatibility with the old parameter type.
		{
			$width .= 'px';
		}
		$request->setAttribute('width', $width);
		
		$height = $configuration->getFrameHeight();
		if (is_integer($height)) // For compatibility with the old parameter type.
		{
			$height .= 'px';
		}
		$request->setAttribute('height', $height);
		
		// "scrolling" attribute is deprecated, so convert it to style.
		$overflow = $configuration->getScrolling();
		switch ($overflow)
		{
			case 'yes':
				$overflow = 'scroll';
				break;
		
			case 'no':
				$overflow = 'hidden';
				break;
		
			case 'auto':
			default:
				$overflow = 'auto';
				break;
		}
		$request->setAttribute('overflow', $overflow);
		
		if ($this->isInBackofficeEdition())
		{
			return website_BlockView::BACKOFFICE;
		}
		return website_BlockView::SUCCESS;
	}
}