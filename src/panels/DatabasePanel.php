<?php

/**
 * @copyright Frederic G. Østby
 * @license   http://www.makoframework.com/license
 */

namespace mako\toolbar\panels;

use SqlFormatter;

use mako\database\ConnectionManager;
use mako\toolbar\panels\Panel;
use mako\toolbar\panels\PanelInterface;
use mako\utility\Arr;
use mako\view\ViewFactory;

/**
 * Database panel.
 *
 * @author Frederic G. Østby
 */
class DatabasePanel extends Panel implements PanelInterface
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
	 * @access public
	 * @param \mako\view\ViewFactory           $view     View factory instance
	 * @param \mako\database\ConnectionManager $database Connection manager instance
	 */
	public function __construct(ViewFactory $view, ConnectionManager $database)
	{
		parent::__construct($view);

		$this->database = $database;
	}

	/**
	 * Returns the tab label.
	 *
	 * @access public
	 * @return string
	 */
	public function getTabLabel(): string
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
	 * Returns logs with syntax highlighted queries.
	 *
	 * @access public
	 * @param  array $logs Query logs
	 * @return array
	 */
	protected function getLogWithSyntaxHighlightedQueries(array $logs): array
	{
		// Configure the SQL formatter

		SqlFormatter::$pre_attributes = 'style="color: black; background-color: transparent;"';

		// Add syntax highlighting to SQL queries

		foreach($logs as &$log)
		{
			foreach($log as $key => $query)
			{
				$log[$key]['query'] = SqlFormatter::highlight($query['query']);
			}
		}

		return $logs;
	}

	/**
	 * Returns the rendered panel.
	 *
	 * @access public
	 * @return string
	 */
	public function render(): string
	{
		$view = $this->view->create('mako-toolbar::panels.database',
		[
			'logs' => $this->getLogWithSyntaxHighlightedQueries($this->database->getLogs()),
		]);

		return $view->render();
	}
}
