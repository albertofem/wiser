<?php

/*
 * This file is part of Wiser
 *
 * (c) Alberto Fernández <albertofem@gmail.com>
 *
 * For the full copyright and license information, please read
 * the LICENSE file that was distributed with this source code.
 */

namespace Wiser\Listener;

use Wiser\Event\GetViewEvent;

interface RenderStartInterface
{
	public function onRenderStart(GetViewEvent $event);
}
