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

use Wiser\Wiser;
use Wiser\Environment;
use Wiser\View;

class WiserTest extends \PHPUnit_Framework_TestCase
{
	public function testCreateInstanceWithoutEnvironment()
	{
		$wiser = new Wiser;

		$this->assertTrue($wiser->getEnvironment() instanceof Environment);
	}

	public function testCreateInstanceWithArrayParameters()
	{
		$wiser = new Wiser(array('debug' => true));

		$this->assertTrue($wiser->getEnvironment() instanceof Environment);
	}

	/**
 	 * @expectedException \InvalidArgumentException
	 */
	public function testCreateInstanceWithInvalidParameters()
	{
		new Wiser(array('invalid' => true));
	}

	public function testCreateInstanceWithEnvironment()
	{
		$wiser = new Wiser(new Environment);

		$this->assertTrue($wiser->getEnvironment() instanceof Environment);
	}

	/**
 	 * @expectedException \InvalidArgumentException
	 */
	public function testCreateInstanceWithInvalidEnvironmentObject()
	{
		new Wiser(new \stdClass);
	}

	/**
 	 * @expectedException \InvalidArgumentException
	 */
	public function testTemplateIsAmbiguousException()
	{
		$wiser = new Wiser(array('templatePath' => array(__DIR__ . '/Fixture/', __DIR__ . '/Fixture/dir1/')));

		$wiser->getView('basic_template.html.php');
	}

	public function testGetView()
	{
		$wiser = new Wiser(array('templatePath' => __DIR__ . '/Fixture/'));
		$view = $wiser->getView("basic_template.html.php");

		$this->assertTrue($view instanceof View);
	}

	public function testGetViewLazyLoading()
	{
		$wiser = new Wiser(array('templatePath' => __DIR__ . '/Fixture/'));
		$viewFirst = $wiser->getView("basic_template.html.php");
		$viewSecond = $wiser->getView("basic_template.html.php");

		$this->assertTrue($viewFirst === $viewSecond);
	}
}
