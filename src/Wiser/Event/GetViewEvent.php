<?php

/*
 * Copyright (c) 2012 Arulu Inversiones SL
 * Todos los derechos reservados
 */

namespace Wiser\Event;

use Wiser\View;
use Symfony\Component\EventDispatcher\Event;

class GetViewEvent extends Event
{
	/**
	 * @var \Wiser\View
	 */
	protected $view;

	public function __construct(View $view)
	{
		$this->view = $view;
	}

	/**
	 * @return \Wiser\View
	 */
	public function getView()
	{
		return $this->view;
	}

	/**
	 * @param \Wiser\View $view
	 */
	public function setView(View $view)
	{
		$this->view = $view;
	}
}
