<?php

/**
 * @copyright Frederic G. Østby
 * @license   http://www.makoframework.com/license
 */

namespace mako\toolbar;

use Closure;

use mako\http\Request;
use mako\http\Response;
use mako\http\routing\middleware\Middleware;
use mako\toolbar\Toolbar;

/**
 * Toolbar middleware.
 *
 * @author Frederic G. Østby
 */
class ToolbarMiddleware extends Middleware
{
	/**
	 * Toolbar.
	 *
	 * @var \mako\toolbar\Toolbar
	 */
	protected $toolbar;

	/**
	 * Constructor.
	 *
	 * @param \mako\toolbar\Toolbar $toolbar Toolbar
	 */
	public function __construct(Toolbar $toolbar)
	{
		$this->toolbar = $toolbar;
	}

	/**
	 * {@inheritdoc}
	 */
	public function execute(Request $request, Response $response, Closure $next): Response
	{
		$response = $next($request, $response);

		$body = $response->getBody();

		if($response->getType() === 'text/html' && (is_string($body) || (is_object($body) && method_exists($body, '__toString'))))
		{
			$response->body(str_replace('</body>', $this->toolbar->render() . '</body>', $body));
		}

		return $response;
	}
}
