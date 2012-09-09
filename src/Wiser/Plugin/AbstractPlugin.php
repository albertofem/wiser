<?php

/*
 * This file is part of Wiser
 *
 * (c) Alberto Fernández <albertofem@gmail.com>
 *
 * For the full copyright and license information, please read
 * the LICENSE file that was distributed with this source code.
 */

namespace Wiser\Plugin;

abstract class AbstractPlugin
{
	protected $config;

	public function __construct(Array $config = array())
	{
		$this->config = $config;
	}

	public function getSubscribedEvents()
	{
		return array();
	}

	abstract public function getName();
}
