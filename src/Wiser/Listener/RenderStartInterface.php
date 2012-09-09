<?php

/*
 * Copyright (c) 2012 Arulu Inversiones SL
 * Todos los derechos reservados
 */

namespace Wiser\Listener;

use Wiser\Event\GetViewEvent;

interface RenderStartInterface
{
	public function onRenderStart(GetViewEvent $event);
}
