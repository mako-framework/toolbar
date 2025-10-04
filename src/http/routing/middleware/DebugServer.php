<?php

/**
 * @copyright Frederic G. Østby
 * @license   http://www.makoframework.com/license
 */

namespace mako\toolbar\http\routing\middleware;

use Closure;
use DateTimeInterface;
use mako\application\Application;
use mako\database\ConnectionManager;
use mako\http\Request;
use mako\http\Response;
use mako\http\routing\middleware\MiddlewareInterface;
use mako\toolbar\console\DebugServerClient;
use mako\toolbar\logger\MonologHandler;
use Throwable;

use function array_map;
use function memory_get_peak_usage;
use function microtime;

/**
 * Debug server middleware.
 */
class DebugServer implements MiddlewareInterface
{
	/**
	 * Constructor.
	 */
	public function __construct(
		protected DebugServerClient $client,
		protected Application $app,
		protected ?ConnectionManager $database
	) {
	}

	/**
	 * Collects log entries.
	 */
	protected function collectLogEntries(): array
	{
		$container = $this->app->getContainer();

		if ($container->has(MonologHandler::class)) {
			return array_map(static fn ($entry) => [
				'level' => $entry->level->value,
				'time' => $entry->datetime->format(DateTimeInterface::ATOM),
				'message' => $entry->message,
			], $container->get(MonologHandler::class)->getEntries());
		}

		return [];
	}

	/**
	 * Collects data to send to the server.
	 */
	protected function collectData(Request $request, Response $response, ?Throwable $exception): array
	{
		if ($exception !== null) {
			$exception = [
				'name' => $exception::class,
				'message' => $exception->getMessage(),
			];
		}

		return [
			'exception' => $exception,
			'request' => [
				'method' => $request->getMethod(),
				'route' => $request->getRoute()?->getRoute(),
				'path' => $request->getPath(),
				'type' => $request->getContentType(),
				'ip' => $request->getIp(),
			],
			'response' => [
				'code' => $response->getStatus()->value,
				'type' => $response->getType(),
				'charset' => $response->getCharset(),
			],
			'performance' => [
				'executionTime' => microtime(true) - $this->app->getStartTime(),
				'peakMemoryUsage' => memory_get_peak_usage(),
			],
			'log' => $this->collectLogEntries(),
		];
	}

	/**
	 * Sends data to the server.
	 */
	protected function sendToServer(array $data): void
	{
		try {
			$this->client->sendData($data);
		}
		catch (Throwable) {
			// Fail silently if we were unable to send data
		}
	}

	/**
	 * Collects data and sends it to the debug server.
	 */
	protected function collectDataAndSendToServer(Request $request, Response $response, ?Throwable $exception): void
	{
		$data = $this->collectData($request, $response, $exception);

		$this->sendToServer($data);
	}

    /**
     * {@inheritDoc}
     */
    public function execute(Request $request, Response $response, Closure $next): Response
    {
		$exception = null;

		try {
			$response =  $next($request, $response);
		}
		catch (Throwable $e) {
			throw $exception = $e;
		}
		finally {
			$this->collectDataAndSendToServer($request, $response, $exception);
		}

		return $response;
	}
}
