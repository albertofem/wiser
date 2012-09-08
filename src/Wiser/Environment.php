<?php

/*
 * This file is part of Wiser
 *
 * (c) Alberto Fernández <albertofem@gmail.com>
 *
 * For the full copyright and license information, please read
 * the LICENSE file that was distributed with this source code.
 */

namespace Wiser;

class Environment
{
	const VERSION = '0.1-alpha';

	protected $cachePath = false;

	protected $templatePath = array();

	protected $globals = array();

	protected $debug = false;

	protected $throwExceptions = true;

	protected $extensions = array();

	public function __construct(Array $parameters = array())
	{
		foreach($parameters as $name => $value)
		{
			$this->setParameter($name, $value);
		}
	}

	public function getParameter($name)
	{
		$this->parameterExists($name);

		return $this->$name;
	}

	public function setParameter($name, $value)
	{
		$this->parameterExists($name);

		$this->$name = $value;
	}

	private function parameterExists($name)
	{
		if(!property_exists($this, $name))
			throw new \InvalidArgumentException("Environment parameter '" .$name. "' doesn't exists");
	}
}
