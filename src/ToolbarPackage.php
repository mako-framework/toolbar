<?php

/**
 * @copyright Frederic G. Østby
 * @license   http://www.makoframework.com/license
 */

namespace mako\toolbar;

use mako\application\Package;
use mako\toolbar\Toolbar;
use mako\toolbar\Monologger;
use mako\toolbar\panels\ConfigPanel;
use mako\toolbar\panels\DatabasePanel;
use mako\toolbar\panels\IncludedFilesPanel;
use mako\toolbar\panels\MonologPanel;
use mako\toolbar\panels\RequestPanel;
use mako\toolbar\panels\ResponsePanel;
use mako\toolbar\panels\SessionPanel;

use Monolog\Logger;

/**
 * Toolbar package.
 *
 * @author  Frederic G. Østby
 */
class ToolbarPackage extends Package
{
	/**
	 * Package name.
	 *
	 * @var string
	 */
	protected $packageName = 'mako/toolbar';

	/**
	 * {@inheritdoc}
	 */
	protected function bootstrap()
	{
		$monologHandler = null;

		// Add logger if monolog is in the container

		if($this->container->has('logger'))
		{
			$monologHandler = new Monologger(Logger::DEBUG, true);

			$this->container->get('logger')->pushHandler($monologHandler);
		}

		// Register the toolbar in the container

		$this->container->registerSingleton(['mako\toolbar\Toolbar', 'toolbar'], function($container) use ($monologHandler)
		{
			$view = $container->get('view');

			$toolbar = new Toolbar($view, $container->get('humanizer'));

			$toolbar->addPanel(new RequestPanel($view, $container->get('request')));

			$toolbar->addPanel(new ResponsePanel($view, $container->get('response')));

			$toolbar->addPanel(new ConfigPanel($view, $container->get('config'), $container->get('app')->getEnvironment()));

			if($container->has('session'))
			{
				$toolbar->addPanel(new SessionPanel($view, $container->get('session')));
			}

			if($container->has('database'))
			{
				$panel = new DatabasePanel($view, $container->get('database'));

				$toolbar->addPanel($panel);

				$toolbar->addTimer('SQL', $panel->getTotalQueryTime());
			}

			if($monologHandler !== null)
			{
				$toolbar->addPanel(new MonologPanel($view, $monologHandler));
			}

			return $toolbar;
		});
	}
}
