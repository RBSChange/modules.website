<?php
interface website_ViewLoadHandler
{
	/**
	 * @param String[] $params
	 */ 
	function setParameters($params);
	
	/**
	 * @param website_BlockActionRequest $request
	 * @param website_BlockActionResponse $response
	 */
	public function execute($request, $response);
}