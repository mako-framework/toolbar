<?php

/**
 * @copyright Frederic G. Østby
 * @license   http://www.makoframework.com/license
 */

namespace mako\toolbar;

use mako\application\Application;
use mako\application\Package;
use mako\config\Config;
use mako\database\ConnectionManager as DatabaseConnectionManager;
use mako\http\Request;
use mako\http\Response;
use mako\http\routing\Routes;
use mako\http\routing\URLBuilder;
use mako\session\Session;
use mako\toolbar\controllers\OPcache;
use mako\toolbar\panels\ConfigPanel;
use mako\toolbar\panels\DatabasePanel;
use mako\toolbar\panels\MonologPanel;
use mako\toolbar\panels\OPcachePanel;
use mako\toolbar\panels\RequestPanel;
use mako\toolbar\panels\ResponsePanel;
use mako\toolbar\panels\SessionPanel;
use mako\utility\Humanizer;
use mako\view\ViewFactory;
use Monolog\Logger as MonoLogger;
use Psr\Log\LoggerInterface;

use function function_exists;

/**
 * Toolbar package.
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
	 * {@inheritDoc}
	 */
	protected function bootstrap(): void
	{
		$monologHandler = null;

		// Add logger if monolog is in the container

		if($this->container->has(LoggerInterface::class))
		{
			$monologHandler = new MonologHandler(MonoLogger::DEBUG, true);

			$logger = $this->container->get(LoggerInterface::class)->getLogger();

			if($logger instanceof MonoLogger)
			{
				$logger->pushHandler($monologHandler);
			}
		}

		// Register the toolbar in the container

		$this->container->registerSingleton(['mako\toolbar\Toolbar', 'toolbar'], static function ($container) use ($monologHandler)
		{
			$view = $container->get(ViewFactory::class);

			$toolbar = new Toolbar($view, $container->get(Humanizer::class), $container->get(Application::class));

			$toolbar->addPanel(new RequestPanel($view, $container->get(Request::class)));

			$toolbar->addPanel(new ResponsePanel($view, $container->get(Response::class)));

			$toolbar->addPanel(new ConfigPanel($view, $container->get(Config::class), $container->get(Application::class)->getEnvironment()));

			if($container->has(Session::class))
			{
				$toolbar->addPanel(new SessionPanel($view, $container->get(Session::class)));
			}

			if($container->has(DatabaseConnectionManager::class))
			{
				$panel = new DatabasePanel($view, $container->get(DatabaseConnectionManager::class));

				$toolbar->addPanel($panel);

				$toolbar->addTimer('SQL', fn () => $panel->getTotalQueryTime());
			}

			if($monologHandler !== null)
			{
				$toolbar->addPanel(new MonologPanel($view, $monologHandler));
			}

			if(function_exists('opcache_get_status'))
			{
				$toolbar->addPanel(new OPcachePanel($view, $container->get(URLBuilder::class)));
			}

			return $toolbar;
		});

		// Register routes

		$this->container->get(Routes::class)->post('/mako.toolbar/opcache/reset', [OPcache::class, 'reset'], 'mako.toolbar.opcache.reset');
	}
}
