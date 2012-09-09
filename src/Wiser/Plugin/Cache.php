<?php

/*
 * This file is part of Wiser
 *
 * (c) Alberto Fernández <albertofem@gmail.com>
 *
 * For the full copyright and license information, please read
 * the LICENSE file that was distributed with this source code.
 */

namespace Wiser\Plugin;

use Wiser\Event\GetViewEvent;
use Wiser\Event;
use Wiser\Listener\RenderStartInterface;
use Wiser\Listener\RenderFinishInterface;
use Wiser\View;

class Cache extends AbstractPlugin
	implements RenderStartInterface, RenderFinishInterface
{
	/**
	 * @var array
	 */
	protected $cachedViews = array();

	/**
	 * @var string
	 */
	protected $cachePath;

	/**
	 * @var \DateInterval
	 */
	protected $cacheExpiration = "P1D";

	/**
	 * @var string
	 */
	protected $cacheExtension = 'html';

	/**
	 * @var array
	 */
	protected $viewHashes = array();

	public function __construct(Array $config = array())
	{
		parent::__construct($config);

		if(!isset($config['cache_path']))
			throw new \InvalidArgumentException("The Cache plugin requires to specify the 'cache_path' option");

		$this->setCachePath($config['cache_path']);

		if(isset($config['cache_expiration']))
			$this->setCacheExpiration($config['cache_expiration']);

		if(isset($config['cache_extension']))
			$this->setCacheExtension($config['cache_extension']);
	}

	private function getViewCacheFile(View $view)
	{
		$hash = $view->getHash();

		return $this->cachePath . $hash . "." . $this->cacheExtension;
	}

	public function onRenderStart(GetViewEvent $event)
	{
		// check cached file
		$alreadyCached = $this->checkCachedView($event->getView());

		if($alreadyCached)
		{
			$event->getView()->setConstructVariables(false);
			$event->getView()->setFileName($this->getViewCacheFile($event->getView()));
		}
	}

	public function onRenderFinish(View $view, $output)
	{
		$this->writeCache($view, $output);
	}

	private function writeCache(View $view, $output)
	{
		$file = $this->getViewCacheFile($view);

		file_put_contents($file, $output);
	}

	public function getName()
	{
		return 'cache';
	}

	public function getSubscribedEvents()
	{
		return array(
			Event::onRenderStart,
			Event::onRenderFinish
		);
	}

	private function setCachePath($path)
	{
		if(!is_writable($path))
			throw new \InvalidArgumentException("Cache path '" .$path. "' is not writable!");

		$this->cachePath = $path;
	}

	private function setCacheExpiration($interval)
	{
		if(is_string($interval))
			$interval = new \DateInterval($interval);

		$this->cacheExpiration = $interval;
	}

	private function setCacheExtension($extension)
	{
		$this->cacheExtension = $extension;
	}

	private function checkCachedView($view)
	{
		$cacheFile = $this->getViewCacheFile($view);

		if(!file_exists($cacheFile))
			return false;

		$checkTime = new \DateTime("now");
		$checkTime->add($this->cacheExpiration);

		$fileDate = new \DateTime("now");
		$fileDate->setTimestamp(filemtime($cacheFile));

		if($fileDate > $checkTime)
			return false;

		return true;
	}
}
