<?php
interface website_PageBlock
{
	/**
	 * Called when the block is inserted into a page content
	 * @param website_persistentdocument_Page $page
	 */
	function onPageInsertion($page);

	/**
	 * Called when the block is removed from a page content
	 * @param website_persistentdocument_Page $page
	 */
	function onPageRemoval($page);
}