<?php

/*
 * This file is part of Wiser
 *
 * (c) Alberto Fernández <albertofem@gmail.com>
 *
 * For the full copyright and license information, please read
 * the LICENSE file that was distributed with this source code.
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
