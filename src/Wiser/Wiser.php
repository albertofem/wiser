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

use Symfony\Component\Finder\Finder;

class Wiser
{
	/**
	 * @var Environment
	 */
	private $environment;

	/**
	 * @var array
	 */
	private $views;

	public function __construct($environment = null)
	{
		if(is_object($environment))
		{
			if(!$environment instanceof Environment)
				throw new \InvalidArgumentException("Environment must be a valid 'Wiser\Environment' object");
		}

		if(is_null($environment))
			$environment = new Environment;

		if(is_array($environment))
			$environment = new Environment($environment);

		$this->environment = $environment;
	}

	/**
	 * @param \Wiser\Environment $environment
	 */
	public function setEnvironment(Environment $environment)
	{
		$this->environment = $environment;
	}

	/**
	 * @return \Wiser\Environment
	 */
	public function getEnvironment()
	{
		return $this->environment;
	}

	public function getView($file)
	{
		$templates = $this->findTemplates($file);

		$fileName = array_keys(iterator_to_array($templates));
		$fileName = array_shift($fileName);

		if(!isset($this->views[$fileName]))
			$this->views[$fileName] = new View($fileName);

		return $this->views[$fileName];
	}

	private function findTemplates($file)
	{
		$finder = new Finder;
		$templates = $finder->files()
			->followLinks()
			->depth(0)
			->in($this->getEnvironment()->getParameter('templatePath'))->name($file);

		if($templates->count() > 1)
			throw new \InvalidArgumentException("The template file '" .$file. "' is ambiguous");

		return $templates;
	}
}
