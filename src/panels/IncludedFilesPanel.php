<?php

/**
 * @copyright Frederic G. Østby
 * @license   http://www.makoframework.com/license
 */

namespace mako\toolbar\panels;

use mako\toolbar\panels\traits\DumperTrait;

use function count;
use function get_included_files;
use function sprintf;

/**
 * Included files panel.
 */
class IncludedFilesPanel extends Panel implements PanelInterface
{
	use DumperTrait;

	/**
	 * Included files.
	 */
	protected array $files;

	/**
	 * Returns the included files.
	 */
	protected function getIncludedFiles(): array
	{
		if (empty($this->files)) {
			$this->files = get_included_files();
		}

		return $this->files;
	}

	/**
	 * {@inheritDoc}
	 */
	public function getTabLabel(): string
	{
		return sprintf('%u included files', count($this->getIncludedFiles()));
	}

	/**
	 * {@inheritDoc}
	 */
	public function render(): string
	{
		$view = $this->view->create('mako-toolbar::panels.included_files', [
			'files' => $this->getIncludedFiles(),
			'dump'  => $this->getDumper(),
		]);

		return $view->render();
	}
}
