<?php
class website_UnavailableSuccessView extends PHPView
{
    /**
     * Execute any presentation logic and set template attributes.
     *
     * @return void
     *
     * @author Sean Kerr (skerr@mojavi.org)
     * @since  1.0.0
     */
    public function execute ()
    {
    	$HTTP_Header= new HTTP_Header();
		$HTTP_Header->sendStatusCode(503);

		// set our template
		$this->setTemplate(
		    Resolver::getInstance('file')
		      ->setPackageName('modules_website')
		      ->setDirectory('templates')
		      ->getPath('Page-Unavailable-Success.php')
		);

		// set the title
		$this->setAttribute('title', 'Unavailable Action');
    }
}