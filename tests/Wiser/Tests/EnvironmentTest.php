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
use Wiser\Extension\Inheritance\InheritanceExtension;
use Wiser\Tests\Fixture\Extension\TestExtension1;
use Wiser\Tests\Fixture\Extension\TestExtension2;
use Wiser\Plugin\Cache;

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
			'templatePath' => array(__DIR__, '/test/'),
			'globals' => array('test' => 500),
			'extensions' => array('some_extension')
		));

		$this->assertTrue(true);
	}

	public function testLoadBuiltInPlugin()
	{
		$env = new Environment(array(
			'cache' => array(
				'cache_path' => __DIR__, '/test/'
			),
		));

		$cache = $env->getPlugin('cache');

		$this->assertTrue($cache instanceof Cache);
	}

	/**
	 * @expectedException \InvalidArgumentException
	 */
	public function testNonExistantPlugin()
	{
		$this->getEnvironment()->getPlugin('invalid');
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

	public function testAddExtension()
	{
		$extension = new InheritanceExtension;

		$this->getEnvironment()->addExtension($extension);

		$this->assertTrue($this->getEnvironment()->getExtension('inheritance') === $extension);
	}

	/**
	 * @expectedException \InvalidArgumentException
	 */
	public function testGetInvalidExtensionException()
	{
		$this->getEnvironment()->getExtension('invalid');
	}

	public function testGetExtensionFromMethodCall()
	{
		$extension = new TestExtension1;

		$this->getEnvironment()->addExtension($extension);

		$this->assertTrue($this->getEnvironment()->getExtensionFromCall('methodOne') === $extension);
	}

	public function testOverrideExtensionCall()
	{
		$extension1 = new TestExtension1;
		$extension2 = new TestExtension2;

		$this->getEnvironment()->addExtension($extension1);
		$this->getEnvironment()->addExtension($extension2);

		$this->assertTrue($this->getEnvironment()->getExtensionFromCall('sameMethod') === $extension2);
	}

	/**
	 * @expectedException \InvalidArgumentException
	 */
	public function testExtensionCallDoesntExists()
	{
		$this->getEnvironment()->getExtensionFromCall('invalid');
	}

	public function testCallExtension()
	{
		$extension = new TestExtension1;

		$this->getEnvironment()->addExtension($extension);

		$output = $this->getEnvironment()->callExtension('methodOne');

		$this->assertEquals('test', $output);
	}

	public function testRegisterGlobal()
	{
		$extension = new TestExtension1;

		$this->getEnvironment()->addExtension($extension);

		$this->assertArrayHasKey('test', $this->getEnvironment()->getGlobals());
		$this->assertEquals('global', $this->getEnvironment()->getGlobals()['test']);
	}
}
