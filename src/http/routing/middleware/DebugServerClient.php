<?php

namespace mako\toolbar\http\routing\middleware;

use mako\config\attributes\syringe\InjectConfig;
use mako\security\Signer;

use function fclose;
use function fsockopen;
use function fwrite;
use function json_encode;
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
		protected Signer $signer,
		#[InjectConfig('mako-toolbar::config.debug_server_client.address')] protected string $address,
		#[InjectConfig('mako-toolbar::config.debug_server_client.port')] protected int $port,
		#[InjectConfig('mako-toolbar::config.debug_server_client.socket_timeout')] protected float $socketTimeout,
		#[InjectConfig('mako-toolbar::config.debug_server_client.stream_timeout')] protected int $streamTimeout
	) {
	}

	/**
	 * Sends data to the debug server.
	 */
	public function sendData(array $data): void
	{
		$socket = fsockopen($this->address, $this->port, timeout: $this->socketTimeout);
		stream_set_timeout($socket, seconds: $this->streamTimeout);
		fwrite($socket, $this->signer->sign(json_encode($data)));
		fclose($socket);
	}
}
