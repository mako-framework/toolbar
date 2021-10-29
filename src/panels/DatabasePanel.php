<?php

/**
 * @copyright Frederic G. Østby
 * @license   http://www.makoframework.com/license
 */

namespace mako\toolbar\panels;

use Doctrine\SqlFormatter\HtmlHighlighter;
use Doctrine\SqlFormatter\SqlFormatter;
use mako\database\ConnectionManager;
use mako\view\ViewFactory;

use function array_column;
use function array_sum;
use function count;
use function round;
use function sprintf;

/**
 * Database panel.
 */
class DatabasePanel extends Panel implements PanelInterface
{
	/**
	 * Conenction manager instance.
	 *
	 * @var \mako\database\ConnectionManager
	 */
	protected $database;

	/**
	 * Constructor.
	 *
	 * @param \mako\view\ViewFactory           $view     View factory instance
	 * @param \mako\database\ConnectionManager $database Connection manager instance
	 */
	public function __construct(ViewFactory $view, ConnectionManager $database)
	{
		parent::__construct($view);

		$this->database = $database;
	}

	/**
	 * Returns the total query count.
	 *
	 * @return int
	 */
	public function getTotalQueryCount(): int
	{
		return count($this->database->getLogs(false));
	}

	/**
	 * Returns the total query time.
	 *
	 * @return float
	 */
	public function getTotalQueryTime(): float
	{
		return array_sum(array_column($this->database->getLogs(false), 'time'));
	}

	/**
	 * {@inheritDoc}
	 */
	public function getTabLabel(): string
	{
		if(($totalQueries = $this->getTotalQueryCount()) === 0)
		{
			return sprintf('%u database queries', $totalQueries);
		}
		else
		{
			return sprintf('%u database queries ( %f seconds )', $totalQueries, round($this->getTotalQueryTime(), 4));
		}
	}

	/**
	 * Returns logs with syntax highlighted queries.
	 *
	 * @param  array $logs Query logs
	 * @return array
	 */
	protected function getFormattedLog(array $logs): array
	{
		// Configure the SQL highlighter

		$highlighter = new HtmlHighlighter
		([
			HtmlHighlighter::HIGHLIGHT_PRE => 'style="color: inherit; background-color: transparent;"',
		]);

		// Add syntax highlighting to SQL queries

		$formatter = new SqlFormatter($highlighter);

		foreach($logs as &$log)
		{
			foreach($log as $key => $query)
			{
				$log[$key]['query'] = $formatter->format($query['query']);
			}
		}

		return $logs;
	}

	/**
	 * {@inheritDoc}
	 */
	public function render(): string
	{
		$view = $this->view->create('mako-toolbar::panels.database',
		[
			'logs' => $this->getFormattedLog($this->database->getLogs()),
		]);

		return $view->render();
	}
}
