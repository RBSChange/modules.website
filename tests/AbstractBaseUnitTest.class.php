<?php
abstract class website_tests_AbstractBaseUnitTest extends website_tests_AbstractBaseTest
{
	/**
	 * @return void
	 */
	public function prepareTestCase()
	{
		$this->resetDatabase();
	}
}