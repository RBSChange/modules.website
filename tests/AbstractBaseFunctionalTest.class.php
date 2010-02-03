<?php
abstract class website_tests_AbstractBaseFunctionalTest extends website_tests_AbstractBaseTest
{
	/**
	 * @return void
	 */
	public function prepareTestCase()
	{
		$this->loadSQLResource('functional-test.sql', true, false);
	}
}