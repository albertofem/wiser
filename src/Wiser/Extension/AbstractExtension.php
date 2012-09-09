<?php

/*
 * This file is part of Wiser
 *
 * (c) Alberto Fernández <albertofem@gmail.com>
 *
 * For the full copyright and license information, please read
 * the LICENSE file that was distributed with this source code.
 */

namespace Wiser\Extension;

use Wiser\Plugin\AbstractPlugin;

abstract class AbstractExtension extends AbstractPlugin
{

	public function getGlobals()
	{
		return array();
	}
}
