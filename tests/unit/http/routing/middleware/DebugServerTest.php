<?php

/**
 * @copyright Frederic G. Østby
 * @license   http://www.makoframework.com/license
 */

namespace mako\toolbar\tests\unit\http\routing\middleware;

use mako\application\Application;
use mako\database\ConnectionManager;
use mako\http\Request;
use mako\http\Response;
use mako\http\response\Status;
use mako\http\routing\Route;
use mako\syringe\Container;
use mako\toolbar\console\DebugServerClient;
use mako\toolbar\http\routing\middleware\DebugServer;
use mako\toolbar\logger\MonologHandler;
use mako\toolbar\tests\TestCase;
use Mockery;
use Mockery\MockInterface;
use PHPUnit\Metadata\Group;
use RuntimeException;

#[Group('unit')]
class DebugServerTest extends TestCase
{
	/**
	 *
	 */
	public function getApplication(): Application&MockInterface
	{
		$monologHandler = Mockery::mock(MonologHandler::class);

		$monologHandler->shouldReceive('getEntries')->andReturn([]);

		//

		$container = Mockery::mock(Container::class);

		$container->shouldReceive('has')->with(MonologHandler::class)->andReturn(true);

		$container->shouldReceive('get')->with(MonologHandler::class)->andReturn($monologHandler);

		//

		$application = Mockery::mock(Application::class);

		$application->shouldReceive('getContainer')->andReturn($container);

		$application->shouldReceive('getStartTime')->andReturn(1759769730.83);

		return $application;
	}

	/**
	 *
	 */
	public function getConnectionManager(): ConnectionManager&MockInterface
	{
		$connectionManager = Mockery::mock(ConnectionManager::class);

		$connectionManager->shouldReceive('getLogs')->with(false)->andReturn([]);

		return $connectionManager;
	}

	/**
	 *
	 */
	public function getRequest(): MockInterface&Request
	{
		$route = Mockery::mock(Route::class);

		$route->shouldReceive('getRoute')->andReturn('/article/{id}');

		//

		$request = Mockery::mock(Request::class);

		$request->shouldReceive('getMethod')->andReturn('GET');
		$request->shouldReceive('getRoute')->andReturn($route);
		$request->shouldReceive('getPath')->andReturn('/article/123');
		$request->shouldReceive('getContentType')->andReturn('application/json');
		$request->shouldReceive('getIp')->andReturn('127.0.0.1');

		return $request;
	}

	/**
	 *
	 */
	public function getResponse(): MockInterface&Response
	{
		$response = Mockery::mock(Response::class);

		$response->shouldReceive('getStatus')->andReturn(Status::OK);
		$response->shouldReceive('getType')->andReturn('application/json');
		$response->shouldReceive('getCharset')->andReturn('UTF-8');

		return $response;
	}

	/**
	 *
	 */
	public function testHandleNormalRequestWithNoConnectionManager(): void
	{
		$client = Mockery::mock(DebugServerClient::class);

		$client->shouldReceive('sendData')->once()->with(Mockery::on(function ($data) {
			return
			array_key_exists('exception', $data)
			&& array_key_exists('request', $data)
			&& array_key_exists('response', $data)
			&& array_key_exists('performance', $data)
			&& is_null($data['exception']);
		}));

		$debugServer = new DebugServer($client, $this->getApplication(), null);

		$response = $debugServer->execute(
			$this->getRequest(),
			$this->getResponse(),
			static function (Request $request, Response $response): Response {
				return $response;
			}
		);

		$this->assertInstanceOf(Response::class, $response);
	}

	/**
	 *
	 */
	public function testHandleNormalRequestWithConnectionManager(): void
	{
		$client = Mockery::mock(DebugServerClient::class);

		$client->shouldReceive('sendData')->once()->with(Mockery::on(function ($data) {
			return
			array_key_exists('exception', $data)
			&& array_key_exists('request', $data)
			&& array_key_exists('response', $data)
			&& array_key_exists('performance', $data)
			&& is_null($data['exception']);
		}));

		$debugServer = new DebugServer($client, $this->getApplication(), $this->getConnectionManager());

		$response = $debugServer->execute(
			$this->getRequest(),
			$this->getResponse(),
			static function (Request $request, Response $response): Response {
				return $response;
			}
		);

		$this->assertInstanceOf(Response::class, $response);
	}

	/**
	 *
	 */
	public function testHandleNormalRequestWithNoConnectionManagerAndException(): void
	{
		$this->expectException(RuntimeException::class);

		$client = Mockery::mock(DebugServerClient::class);

		$client->shouldReceive('sendData')->once()->with(Mockery::on(function ($data) {
			return
			array_key_exists('exception', $data)
			&& array_key_exists('request', $data)
			&& array_key_exists('response', $data)
			&& array_key_exists('performance', $data)
			&& is_array($data['exception'])
			&& $data['exception']['name'] === RuntimeException::class
			&& $data['exception']['message'] === 'On no!';
		}));

		$debugServer = new DebugServer($client, $this->getApplication(), null);

		$response = $debugServer->execute(
			$this->getRequest(),
			$this->getResponse(),
			static function (Request $request, Response $response): Response {
				throw new RuntimeException('On no!');
				return $response;
			}
		);

		$this->assertInstanceOf(Response::class, $response);
	}
}
