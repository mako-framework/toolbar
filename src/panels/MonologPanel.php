<?php

/**
 * @copyright  Frederic G. Østby
 * @license    http://www.makoframework.com/license
 */

namespace mako\toolbar\panels;

use mako\toolbar\Monologger;
use mako\toolbar\panels\Panel;
use mako\toolbar\panels\PanelInterface;
use mako\view\ViewFactory;

/**
 * Monolog panel.
 *
 * @author  Frederic G. Østby
 */

class MonologPanel extends Panel implements PanelInterface
{
	/**
	 * Monologger.
	 *
	 * @var \mako\toolbar\Monologger
	 */

	protected $monologger;

	/**
	 * Constructor.
	 *
	 * @access  public
	 * @param   \mako\view\ViewFactory    $view        View factory instance
	 * @param   \mako\toolbar\Monologger  $monologger  Monologger
	 */

	public function __construct(ViewFactory $view, Monologger $monologger)
	{
		parent::__construct($view);

		$this->monologger = $monologger;
	}

	/**
	 * Returns the tab label.
	 *
	 * @access  public
	 * @return  string
	 */

	public function getTabLabel()
	{
		return sprintf('%u log entries', $this->monologger->getEntryCount());
	}

	/**
	 * Returns a helper funtion.
	 *
	 * @access  protected
	 * @return  \Closure
	 */

	protected function getLevelHelper()
	{
		return function($level)
		{
			switch($level)
			{
				case 100:
					return 'debug';
				case 200:
					return 'info';
				case 250:
					return 'notice';
				case 300:
					return 'warning';
				case 400:
					return 'error';
				case 500:
					return 'critical';
				case 550:
					return 'alert';
				case 600:
					return 'emergency';
			}
		};
	}

	/**
	 * Returns the rendered panel.
	 *
	 * @access  public
	 * @return  string
	 */

	public function render()
	{
		$view = $this->view->create('mako-toolbar::panels.monolog',
		[
			'entries'      => $this->monologger->getEntries(),
			'level_helper' => $this->getLevelHelper(),
		]);

		return $view->render();
	}
}