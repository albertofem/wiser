<?php

/*
 * Copyright (c) 2012 Arulu Inversiones SL
 * Todos los derechos reservados
 */

namespace Wiser\Tests\Fixture\Extension;

use Wiser\Extension\AbstractExtension;

class TestExtension1 extends AbstractExtension
{
	public function methodOne()
	{
		return 'test';
	}

	public function getGlobals()
	{
		return array('test' => 'global');
	}

	public function sameMethod()
	{
	}

	public function getName()
	{
		return 'extension1';
	}
}
