<?php

/*
 * This file is part of Wiser
 *
 * (c) Alberto Fernández <albertofem@gmail.com>
 *
 * For the full copyright and license information, please read
 * the LICENSE file that was distributed with this source code.
 */

namespace Wiser\Tests;

use Wiser\Environment;

class EnvironmentTest extends \PHPUnit_Framework_TestCase
{
	/**
	 * @var Environment
	 */
	private static $environment;

	public static function setUpBeforeClass()
	{
		self::$environment = new Environment;
	}

	private function getEnvironment()
	{
		return self::$environment;
	}

	/**
 	 * @expectedException \InvalidArgumentException
	 */
	public function testInvalidEnvironmentParameters()
	{
		new Environment(array('invalid_parameter' => 'test'));
	}

	public function testValidEnvironmentParameters()
	{
		new Environment(array(
			'debug' => true,
			'throwExceptions' => true,
			'cachePath' => __DIR__,
			'templatePath' => array(__DIR__, '/test/'),
			'globals' => array('test' => 500),
			'extensions' => array('some_extension')
		));

		$this->assertTrue(true);
	}

	public function testGetEnvironmentParameter()
	{
		$this->assertTrue($this->getEnvironment()->getParameter('debug') == false);
		$this->assertTrue($this->getEnvironment()->getParameter('throwExceptions') == true);
	}

	/**
	 * @expectedException \InvalidArgumentException
	 */
	public function testGetEnvironemntParameterDoesntExists()
	{
		$this->getEnvironment()->getParameter('invalid');
	}
}
