<?php

namespace mako\toolbar\http\routing\middleware;

use mako\config\attributes\syringe\InjectConfig;

use function fclose;
use function fsockopen;
use function fwrite;
use function stream_set_timeout;

/**
 * Debug server client.
 */
class DebugServerClient
{
	/**
	 * Constructor.
	 */
	public function __construct(
		#[InjectConfig('mako-toolbar::config.debug_server_client.address')] protected string $address,
		#[InjectConfig('mako-toolbar::config.debug_server_client.port')] protected int $port,
		#[InjectConfig('mako-toolbar::config.debug_server_client.socket_timeout')] protected float $socketTimeout,
		#[InjectConfig('mako-toolbar::config.debug_server_client.stream_timeout')] protected int $streamTimeout
	) {
	}

	/**
	 * Sends data to the debug server.
	 */
	public function sendData(string $data): void
	{
		$socket = fsockopen($this->address, $this->port, timeout: $this->socketTimeout);
		stream_set_timeout($socket, seconds: $this->streamTimeout);
		fwrite($socket, $data);
		fclose($socket);
	}
}
