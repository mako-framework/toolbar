<?php

/**
 * @copyright Frederic G. Østby
 * @license   http://www.makoframework.com/license
 */

namespace mako\toolbar\panels;

use mako\toolbar\panels\Panel;
use mako\toolbar\panels\PanelInterface;
use mako\view\ViewFactory;

/**
 * Included files panel.
 *
 * @author Frederic G. Østby
 */
class IncludedFilesPanel extends Panel implements PanelInterface
{
	/**
	 * Included files.
	 *
	 * @var array
	 */
	protected $files;

	/**
	 * Constructor.
	 *
	 * @access public
	 * @param \mako\view\ViewFactory $view View factory instance
	 */
	public function __construct(ViewFactory $view)
	{
		parent::__construct($view);
	}

	/**
	 * Returns the included files.
	 *
	 * @access protected
	 * @return array
	 */
	protected function getIncludedFiles(): array
	{
		if(empty($this->files))
		{
			$this->files = get_included_files();
		}

		return $this->files;
	}

	/**
	 * Returns the tab label.
	 *
	 * @access public
	 * @return string
	 */
	public function getTabLabel(): string
	{
		return sprintf('%u included files', count($this->getIncludedFiles()));
	}

	/**
	 * Returns the rendered panel.
	 *
	 * @access public
	 * @return string
	 */
	public function render(): string
	{
		$view = $this->view->create('mako-toolbar::panels.included_files',
		[
			'files' => $this->getIncludedFiles(),
		]);

		return $view->render();
	}
}
