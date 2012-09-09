<?php

/*
 * Copyright (c) 2012 Arulu Inversiones SL
 * Todos los derechos reservados
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
