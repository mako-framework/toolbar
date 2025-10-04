<?php

/**
 * @copyright Frederic G. Østby
 * @license   http://www.makoframework.com/license
 */

namespace mako\toolbar\http\routing\middleware;

use Closure;
use mako\http\Request;
use mako\http\Response;
use mako\http\routing\middleware\MiddlewareInterface;
use mako\toolbar\Toolbar as MakoToolbar;

use function is_object;
use function is_string;
use function method_exists;
use function str_replace;

/**
 * Toolbar middleware.
 */
class Toolbar implements MiddlewareInterface
{
	/**
	 * Constructor.
	 */
	public function __construct(
		protected MakoToolbar $toolbar
	) {
	}

	/**
	 * {@inheritDoc}
	 */
	public function execute(Request $request, Response $response, Closure $next): Response
	{
		$response = $next($request, $response);

		$body = $response->getBody();

		if ($response->getType() === 'text/html' && (is_string($body) || (is_object($body) && method_exists($body, '__toString')))) {
			$response->setBody(str_replace('</body>', $this->toolbar->render() . '</body>', $body));
		}

		return $response;
	}
}
