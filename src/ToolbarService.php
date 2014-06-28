<?php

/**
 * @copyright  Frederic G. Østby
 * @license    http://www.makoframework.com/license
 */

namespace mako\toolbar;

use \mako\toolbar\Toolbar;
use \mako\toolbar\panels\DatabasePanel;
use \mako\toolbar\panels\IncludedFilesPanel;
use \mako\toolbar\panels\SuperglobalsPanel;

/**
 * Toolbar service.
 *
 * @author  Frederic G. Østby
 */

class ToolbarService extends \mako\application\services\Service
{
	/**
	 * Registers the service.
	 * 
	 * @access  public
	 */

	public function register()
	{
		$this->container->registerSingleton(['mako\toolbar\Toolbar', 'toolbar'], function($container)
		{
			$view = $container->get('view');

			$toolbar = new Toolbar($view, $container->get('humanizer'));

			// Register panels

			$toolbar->addPanel(new SuperglobalsPanel($view));

			if($container->has('database'))
			{
				$toolbar->addPanel(new DatabasePanel($view, $container->get('database')));
			}

			$toolbar->addPanel(new IncludedFilesPanel($view));

			// Return the toolbar

			return $toolbar;
		});
	}
}