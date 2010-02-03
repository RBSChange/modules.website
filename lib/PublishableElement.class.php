<?php
/**
 * @date Tue May 22 14:55:41 CEST 2007
 * @author intbonjf
 *
 *******************************************************************************
 * Every document that wants to appear in the website must implement this      *
 * interface.                                                                  *
 *******************************************************************************
 */
interface website_PublishableElement
{
	/**
	 * @return string
	 */
	public function getNavigationtitle();

	/**
	 * Indicates whether the element is:
	 * - visible in the sitemap and in the menus
	 * - visible in the sitemap only
	 * - visible nowhere (hidden in all navigation elements)
	 *
	 * @return integer WebsiteConstants::VISIBILITY_HIDDEN, 
	 * 		WebsiteConstants::VISIBILITY_VISIBLE, 
	 * 		WebsiteConstants::VISIBILITY_HIDDEN_IN_MENU_ONLY
	 * 		WebsiteConstants::VISIBILITY_HIDDEN_IN_SITEMAP_ONLY
	 *
	 * @see WebsiteHelper, WebsiteConstants
	 */
	public function getNavigationVisibility();
	
	
	/**
	 * @return string | null
	 */
	public function getNavigationURL();

}