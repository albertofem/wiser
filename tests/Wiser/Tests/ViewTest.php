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

use Wiser\View;
use Wiser\Wiser;

class ViewTest extends \PHPUnit_Framework_TestCase
{
	/**
 	 * @expectedException \InvalidArgumentException
	 */
	public function testFileDoesntExistsOrNotReadable()
	{
		new View('_i_dont_exists.html.bak.jpg.nope.gif');
	}

	public function testBasicRender()
	{
		$view = new View(__DIR__ . '/Fixture/basic_template.html.php');

		$output = $view->render();

		$this->assertEquals("<h1>Test!</h1>", $output);
	}


	public function testRenderWithVariables()
	{
		$view = new View(__DIR__ . '/Fixture/variables/variables.html.php');

		$output = $view->render(array('myVar' => 'test'));

		$this->assertEquals('test', $output);
	}

	public function testRenderWithVariableUsingOldMethod()
	{
		$view = new View(__DIR__ . '/Fixture/variables/variables_old.html.php');

		$output = $view->render(array('myVar' => 'test', 'otherVar' => 'jesus'));

		$this->assertEquals('test-jesus', $output);
	}

	/**
	 * @expectedException \ErrorException
	 */
	public function testGetInvalidParameter()
	{
		$view = new View(__DIR__ . '/Fixture/variables/variables_old.html.php');

		$view->get('invalid');
	}

	/*
	public function testSimpleInheritance()
	{
		$wiser = new Wiser(array('templatePath' => __DIR__ . '/Fixture/inheritance/'));
		$view = new VIew(__DIR__ . '/Fixture/inheritance/child.html.php');
		$view->setWiser($wiser);

		$output = $view->render();

		$this->assertEquals('Test-Parent content-hola!', $output);
	}*/
}
