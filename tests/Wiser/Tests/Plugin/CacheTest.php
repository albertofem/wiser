<?php

/*
 * Copyright (c) 2012 Arulu Inversiones SL
 * Todos los derechos reservados
 */

namespace Wiser\Tests\Cache;

use Wiser\Plugin\Cache;
use Wiser\View;
use Wiser\Event\GetViewEvent;
use Wiser\Wiser;
use Symfony\Component\Finder\Finder;

class CacheTest extends \PHPUnit_Framework_TestCase
{
	public static function deleteCacheDir()
	{
		$finder = new Finder;
		$finder->files()->in(__DIR__ . '/../Fixture/cache_dir/')
			->name("*.*");

		foreach($finder as $file)
		{
			unlink($file);
		}
	}

	public static function setUpBeforeClass()
	{
		self::deleteCacheDir();
	}

	public static function tearDownAfterClass()
	{
		self::deleteCacheDir();
	}

	/**
 	 * @expectedException \InvalidArgumentException
	 */
	public function testInvalidConfiguration()
	{
		new Cache();
	}

	/**
	 * @expectedException \InvalidArgumentException
	 */
	public function testCacheDirNotWritable()
	{
		new Cache(array('cache_path' => '/i_Cant_pssibly/exstis/jasjdas'));
	}

	public function testBasicCacheView()
	{
		$view = new View(__DIR__ . '/../Fixture/basic_template.html.php');
		$event = new GetViewEvent($view);

		$path = __DIR__. '/../Fixture/cache_dir/';

		$cache = new Cache(array(
			'cache_path' => $path,
			'cache_expiration' => 'P1D'
		));

		// simulate events
		$cache->onRenderStart($event);
		$output = $view->render();
		$cache->onRenderFinish($view, $output);

		$cache->onRenderStart($event);

		$fileName = $view->getFileName();
		$expected = $path . $view->getHash() . '.html';

		$this->assertEquals($expected, $fileName);
	}
}
