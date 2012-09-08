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

use Wiser\View\Block;

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
		$this->setParameters($parameters);

		return $this->getOutput();
	}

	public function block($name)
	{
		$this->currentBlock = $name;
		$this->blocks[$name] = new Block($name);

		ob_start();
	}

	public function parent()
	{
		if(is_null($this->parent))
			throw new \ErrorException("Cannot display parent content in block '" .$this->currentBlock. "' as this view has no parent");
	}

	public function endblock()
	{
		$blockContent = ob_get_clean();

		$this->blocks[$this->currentBlock]->setContent($blockContent);
	}

	private function getOutput()
	{
		ob_start();

		if(!is_null($this->parent))
			$this->parent->getOutput();

		// construct variables
		foreach($this->parameters as $variable => $value)
		{
			$$variable = $value;
		}

		require $this->fileName;

		return ob_get_clean();
	}

	public function extend($file)
	{
		$this->parent = $this->wiser->getView($file);
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
}
