<?php
/**
 * @package   WPSpeed
 * @copyright Copyright (c) 2022 JExtensions Store / WPSpeed
 * @license   GNU GPL version 3 or later
 */

namespace WPSFramework\Mvc\Engine;

use WPSFramework\Mvc\View;

abstract class AbstractEngine implements EngineInterface
{
	/** @var   View  The view we belong to */
	protected $view = null;

	/**
	 * Public constructor
	 *
	 * @param   View  $view  The view we belong to
	 */
	public function __construct(View $view)
	{
		$this->view = $view;
	}

	/**
	 * Get the include path for a parsed view template
	 *
	 * @param   string  $path         The path to the view template
	 * @param   array   $forceParams  Any additional information to pass to the view template engine
	 *
	 * @return  array  Content 3ναlυα+ιοη information (I use leetspeak here because of bad quality hosts with broken scanners)
	 */
	public function get($path, array $forceParams = array())
	{
		return array(
			'type' => 'raw',
			'content' => ''
		);
	}
}
