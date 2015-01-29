<?php

/**
 * @copyright  Frederic G. Østby
 * @license    http://www.makoframework.com/license
 */

namespace mako\toolbar;

use mako\application\Package;
use mako\toolbar\Toolbar;
use mako\toolbar\panels\ConfigPanel;
use mako\toolbar\panels\DatabasePanel;
use mako\toolbar\panels\IncludedFilesPanel;
use mako\toolbar\panels\SessionPanel;
use mako\toolbar\panels\SuperglobalsPanel;

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
		$this->container->registerSingleton(['mako\toolbar\Toolbar', 'toolbar'], function($container)
		{
			$view = $container->get('view');

			$toolbar = new Toolbar($view, $container->get('humanizer'));

			$toolbar->addPanel(new SuperglobalsPanel($view));

			$toolbar->addPanel(new ConfigPanel($view, $container->get('config')));

			if($container->has('session'))
			{
				$toolbar->addPanel(new SessionPanel($view, $container->get('session')));
			}

			if($container->has('database'))
			{
				$toolbar->addPanel(new DatabasePanel($view, $container->get('database')));
			}

			$toolbar->addPanel(new IncludedFilesPanel($view));

			return $toolbar;
		});
	}
}