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

use Wiser\Plugin\AbstractPlugin;
use Symfony\Component\Finder\Finder;
use Symfony\Component\EventDispatcher\EventDispatcher;
use Wiser\Event\GetViewEvent;

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

	/**
	 * @var EventDispatcher
	 */
	private $eventDispatcher;

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
		$this->eventDispatcher = new EventDispatcher;
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

	public function render($file, Array $parameters = array())
	{
		$view = $this->getView($file);

		$view->render($parameters);
	}

	/**
	 * @param $file
	 *
	 * @return View
	 */
	public function getView($file)
	{
		$templates = $this->findTemplates($file);

		$fileName = array_keys(iterator_to_array($templates));
		$fileName = array_shift($fileName);

		if(!isset($this->views[$fileName]))
		{
			$view = new View($fileName);
			$view->setWiser($this);

			$this->views[$fileName] = $view;
		}

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

	/**
	 * @return \Symfony\Component\EventDispatcher\EventDispatcher
	 */
	public function getEventDispatcher()
	{
		return $this->eventDispatcher;
	}
}
