<?php

/*
 * Copyright (c) 2012 Arulu Inversiones SL
 * Todos los derechos reservados
 */

namespace Wiser\Tests\Fixture\Extension;

use Wiser\Extension\AbstractExtension;

class TestExtension2 extends AbstractExtension
{
	public function sameMethod()
	{
	}

	public function getName()
	{
		return 'extension2';
	}
}
