<?php

/**
 * @copyright Frederic G. Østby
 * @license   http://www.makoframework.com/license
 */

namespace mako\toolbar\panels;

/**
 * Panel interface.
 */
interface PanelInterface
{
	/**
	 * Returns a unique panel id.
	 */
	public function getId(): string;

	/**
	 * Returns the tab label.
	 */
	public function getTabLabel(): string;

	/**
	 * Returns the rendered panel.
	 */
	public function render(): string;
}
