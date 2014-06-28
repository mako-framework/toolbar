<?php

/**
 * @copyright  Frederic G. Østby
 * @license    http://www.makoframework.com/license
 */

namespace mako\toolbar\panels;

use \mako\view\ViewFactory;

/**
 * Included files panel.
 *
 * @author  Frederic G. Østby
 */

class IncludedFilesPanel extends \mako\toolbar\panels\Panel implements \mako\toolbar\panels\PanelInterface
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
	 * @access  public
	 * @param   \mako\view\ViewFactory  $view  View factory instance
	 */

	public function __construct(ViewFactory $view)
	{
		parent::__construct($view);

		$this->files = get_included_files();
	}

	/**
	 * Returns the tab label.
	 * 
	 * @access  public
	 * @return  string
	 */

	public function getTabLabel()
	{
		return sprintf('%u included files', count($this->files));
	}

	/**
	 * Returns the rendered panel.
	 * 
	 * @access  public
	 * @return  string
	 */

	public function render()
	{
		$view = $this->view->create('toolbar::panels.included_files',
		[
			'files' => $this->files,
		]);

		return $view->render();
	}
}