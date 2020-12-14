<?php

/**
 * @copyright Frederic G. Østby
 * @license   http://www.makoframework.com/license
 */

namespace mako\toolbar;

use Closure;
use mako\http\Request;
use mako\http\Response;
use mako\http\routing\middleware\MiddlewareInterface;

use function is_object;
use function is_string;
use function method_exists;
use function str_replace;

/**
 * Toolbar middleware.
 *
 * @author Frederic G. Østby
 */
class ToolbarMiddleware implements MiddlewareInterface
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
	 * {@inheritDoc}
	 */
	public function execute(Request $request, Response $response, Closure $next): Response
	{
		$response = $next($request, $response);

		$body = $response->getBody();

		if($response->getType() === 'text/html' && (is_string($body) || (is_object($body) && method_exists($body, '__toString'))))
		{
			$response->setBody(str_replace('</body>', $this->toolbar->render() . '</body>', $body));
		}

		return $response;
	}
}
