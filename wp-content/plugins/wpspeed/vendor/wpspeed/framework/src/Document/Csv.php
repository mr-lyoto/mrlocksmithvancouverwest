<?php
/**
 * @package   WPSpeed
 * @copyright Copyright (c) 2022 JExtensions Store / WPSpeed
 * @license   GNU GPL version 3 or later
 */

namespace WPSFramework\Document;
use WPSFramework\Container\Container;
use WPSFramework\Application\Application;

/**
 * Class Json
 *
 * A JSON output implementation
 *
 * @package WPSFramework\Document
 */
class Csv extends Document
{
	public function __construct(Container $container)
	{
		parent::__construct($container);

		$this->mimeType = 'text/csv';
	}

	/**
	 * Outputs the buffer, which is assumed to contain CSV data, to the
	 * browser.
	 *
	 * @return  void
	 */
	public function render()
	{
		$this->addHTTPHeader('Content-Type', $this->getMimeType());

		$name = $this->getName();

		if (!empty($name))
		{
			$this->addHTTPHeader('Content-Disposition', 'attachment; filename="' . $name . '.csv"', true);
		}

		$this->outputHTTPHeaders();

        echo $this->getBuffer();
	}
}
