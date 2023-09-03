<?php

/**
 * @copyright Frederic G. Østby
 * @license   http://www.makoframework.com/license
 */

namespace mako\toolbar;

use Monolog\Handler\AbstractProcessingHandler;
use Monolog\LogRecord;

use function count;

/**
 * Monolog handler.
 */
class MonologHandler extends AbstractProcessingHandler
{
	/**
	 * Log entries.
	 */
	protected array $entries = [];

	/**
	 * {@inheritDoc}
	 */
	protected function write(LogRecord $record): void
	{
		$this->entries[] = $record;
	}

	/**
	 * Returns the number of log entries.
	 */
	public function getEntryCount(): int
	{
		return count($this->entries);
	}

	/**
	 * Returns the log entries.
	 */
	public function getEntries(): array
	{
		return $this->entries;
	}
}
