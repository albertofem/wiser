<?php

/*
 * This file is part of Wiser
 *
 * (c) Alberto Fernández <albertofem@gmail.com>
 *
 * For the full copyright and license information, please read
 * the LICENSE file that was distributed with this source code.
 */

namespace Wiser\Tests\Fixture\Extension;

use Wiser\Extension\AbstractExtension;

class TestExtension1 extends AbstractExtension
{
	public function methodOne()
	{
		return 'test';
	}

	public function getGlobals()
	{
		return array('test' => 'global');
	}

	public function sameMethod()
	{
	}

	public function getName()
	{
		return 'extension1';
	}
}
