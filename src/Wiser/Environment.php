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

use Wiser\Extension\AbstractExtension;
use Wiser\Plugin\AbstractPlugin;
use Wiser\Plugin\Cache;

class Environment
{
	const VERSION = '0.1-alpha';

	/**
	 * @var array
	 */
	protected $templatePath = array('./');

	/**
	 * @var array
	 */
	protected $globals = array();

	/**
	 * @var bool
	 */
	protected $debug = false;

	/**
	 * @var array
	 */
	protected $extensions = array();

	/**
	 * @var array
	 */
	protected $extensionCalls = array();

	/**
	 * @var array
	 */
	protected $plugins = array();

	/**
	 * @var bool
	 */
	protected $throwExceptions = true;

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
		switch($name)
		{
			case 'extension':
				$this->addExtension($value);
			break;

			case 'plugin':
				$this->addPlugin($value);
			break;

			case 'cache':
				$this->addPlugin(new Cache($value));
			break;

			default:
				$this->parameterExists($name);

				$this->$name = $value;
			break;
		}
	}

	private function parameterExists($name)
	{
		if(!property_exists($this, $name))
			throw new \InvalidArgumentException("Environment parameter '" .$name. "' doesn't exists");
	}

	public function addExtension(AbstractExtension $extension)
	{
		$this->extensions[$extension->getName()] = $extension;

		// add calls from this extension and check duplicated
		$extensionCalls = $this->processExtensionCalls($extension);

		$calls = array();

		foreach($extensionCalls as $method)
		{
			$calls[$method] = $extension->getName();
		}

		$this->extensionCalls = array_merge($this->extensionCalls, $calls);
		$this->globals = array_merge($this->globals, $extension->getGlobals());
	}


	public function addPlugin(AbstractPlugin $plugin)
	{
		$this->plugins[$plugin->getName()] = $plugin;
	}

	public function getExtension($name)
	{
		if(!isset($this->extensions[$name]))
			throw new \InvalidArgumentException("The extension '" .$name. "' does not exists or is not registered");

		return $this->extensions[$name];
	}

	public function getExtensionFromCall($method)
	{
		if(!isset($this->extensionCalls[$method]))
			throw new \InvalidArgumentException("The call '" .$method. "' is not defined");

		return $this->getExtension($this->extensionCalls[$method]);
	}

	public function callExtension($method, Array $arguments = array())
	{
		return call_user_func_array(array($this->getExtensionFromCall($method), $method), $arguments);
	}

	private function processExtensionCalls($extension)
	{
		$extensionCalls = get_class_methods($extension);
		
		// we have to delete magic methods and abstractextension ones
		$magicMethods = array(
			"__construct()", 
			"__destruct()", 
			"__call()", 
			"__callStatic()", 
			"__get()", 
			"__set()", 
			"__isset()", 
			"__unset()", 
			"__sleep()", 
			"__wakeup()", 
			"__toString()", 
			"__invoke()", 
			"__set_state()",
			"__clone()"
		);

		$abstractMethods = get_class_methods("Wiser\Extension\AbstractExtension");

		$methods = array_diff($extensionCalls, array_merge($magicMethods, $abstractMethods));

		return $methods;
	}

	public function getGlobals()
	{
		return $this->globals;
	}

	public function getPlugin($name)
	{
		if(!isset($this->plugins[$name]))
			throw new \InvalidArgumentException("The plugin '" .$name. "' does not exists or is not enable");

		return $this->plugins[$name];
	}
}
