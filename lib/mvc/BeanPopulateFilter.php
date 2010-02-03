<?php
interface website_BeanPopulateFilter
{
	/**
	 * @param f_mvc_Bean $bean
	 * @param website_BlockActionRequest $request
	 */
	function execute($bean, $request);
}