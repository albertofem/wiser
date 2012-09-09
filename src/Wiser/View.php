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

use Wiser\Event\GetViewEvent;

class View
{
	/**
	 * @var string
	 */
	private $fileName;

	/**
	 * @var array
	 */
	private $parameters;

	/**
	 * @var View
	 */
	private $parent = null;

	/**
	 * @var Wiser
	 */
	private $wiser;

	/**
	 * @var Array
	 */
	private $blocks = array();

	private $currentBlock;

	private $constructVariables = true;

	private $hash = null;

	public function __construct($filename)
	{
		if(!is_readable($filename))
			throw new \InvalidArgumentException("'" .$filename. "' does not exists or is not readable");

		$this->fileName = $filename;
	}

	public function setWiser(Wiser $wiser)
	{
		$this->wiser = $wiser;
	}

	public function render(Array $parameters = array())
	{
		if(!is_null($this->wiser))
		{
			$this->wiser->getEventDispatcher()->dispatch(Event::onRenderStart, new GetViewEvent($this));
		}

		$this->setParameters($parameters);

		return $this->getOutput();
	}

	private function getOutput()
	{
		ob_start();

		// construct variables
		if($this->constructVariables)
		{
			$this->constructVariables();

			foreach($this->parameters as $variable => $value)
			{
				$$variable = $value;
			}
		}

		require $this->fileName;

		return ob_get_clean();
	}

	private function constructVariables()
	{
		if(!is_null($this->wiser))
		{
			$this->parameters = array_merge($this->parameters, $this->wiser->getEnvironment()->getGlobals());
		}

		return $this->parameters;
	}

	public function setParameters($parameters)
	{
		$this->parameters = $parameters;
	}

	public function getParameters()
	{
		return $this->parameters;
	}

	public function get($parameter)
	{
		if(!isset($this->parameters[$parameter]))
			throw new \ErrorException("Undefined variable '" .$parameter. "'");

		return $this->parameters[$parameter];
	}

	public function __get($parameter)
	{
		return $this->get($parameter);
	}

	public function __call($name, $arguments)
	{
		$this->wiser->getEnvironment()->callExtension($name, $arguments);
	}

	public function setConstructVariables($constructVariables)
	{
		$this->constructVariables = $constructVariables;
	}

	public function getConstructVariables()
	{
		return $this->constructVariables;
	}

	/**
	 * @param string $fileName
	 */
	public function setFileName($fileName)
	{
		$this->fileName = $fileName;
	}

	/**
	 * @return string
	 */
	public function getFileName()
	{
		return $this->fileName;
	}

	public function getHash()
	{
		if(is_null($this->hash))
			$this->hash = md5(spl_object_hash($this));

		return $this->hash;
	}
}
