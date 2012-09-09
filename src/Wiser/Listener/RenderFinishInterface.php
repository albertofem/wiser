<?php

/*
 * Copyright (c) 2012 Arulu Inversiones SL
 * Todos los derechos reservados
 */

namespace Wiser\Listener;

use Wiser\View;

interface RenderFinishInterface
{
	public function onRenderFinish(View $view, $output);
}
