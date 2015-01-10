<?php

/**
 * @copyright  Frederic G. Østby
 * @license    http://www.makoframework.com/license
 */

namespace mako\toolbar\panels;

use mako\view\ViewFactory;

/**
 * Panel interface.
 *
 * @author  Frederic G. Østby
 */

interface PanelInterface
{
	public function getId();
	public function getTabLabel();
	public function render();
}