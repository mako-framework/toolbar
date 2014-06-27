<?php

/**
 * @copyright  Frederic G. Østby
 * @license    http://www.makoframework.com/license
 */

namespace mako\toolbar\panels;

use \mako\view\ViewFactory;
use \mako\database\ConnectionManager;
use \mako\utility\Arr;

/**
 * Database panel.
 *
 * @author  Frederic G. Østby
 */

class DatabasePanel extends \mako\toolbar\panels\Panel implements \mako\toolbar\panels\PanelInterface
{
	/**
	 * Conenction manager instance.
	 * 
	 * @var array
	 */

	protected $database;

	/**
	 * Constructor.
	 * 
	 * @access  public
	 * @param   \mako\view\ViewFactory            $view      View factory instance
	 * @param   \mako\database\ConnectionManager  $database  Connection manager instance
	 */

	public function __construct(ViewFactory $view, ConnectionManager $database)
	{
		parent::__construct($view);

		$this->database = $database;
	}

	/**
	 * Returns the tab label.
	 * 
	 * @access  public
	 * @return  string
	 */

	public function getTabLabel()
	{
		$queryTime  = 0;
		$queryCount = 0;
		
		foreach($this->database->getLogs() as $queryLog)
		{
			$queryTime  += array_sum(Arr::pluck($queryLog, 'time'));
			$queryCount += count($queryLog);
		}

		if($queryCount === 0)
		{
			return sprintf('%u database queries', $queryCount);
		}
		else
		{
			return sprintf('%u database queries ( %f seconds )', $queryCount, round($queryTime, 4));
		}
	}

	/**
	 * Returns the rendered panel.
	 * 
	 * @access  public
	 * @return  string
	 */

	public function render()
	{
		$view = $this->view->create('toolbar::panels.database',
		[
			'logs' => $this->database->getLogs(),
		]);

		return $view->render();
	}
}