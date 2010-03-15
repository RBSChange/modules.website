<?php
interface website_PageBlock
{
	/**
	 * Called when the block is inserted into a page content
	 * @param website_persistentdocument_Page $page
	 * @param Boolean $absolute true if block was introduced considering all versions (langs) of the page. Default value only for compatibility with old interface
	 */
	function onPageInsertion($page, $absolute = false);

	/**
	 * Called when the block is removed from a page content
	 * @param website_persistentdocument_Page $page
	 * @param Boolean $absolute true if block was removed considering all versions (langs) of the page. Default value only for compatibility with old interface
	 */
	function onPageRemoval($page, $absolute = false);
}