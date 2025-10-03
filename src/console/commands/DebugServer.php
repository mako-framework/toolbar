<?php

/**
 * @copyright Frederic G. Østby
 * @license   http://www.makoframework.com/license
 */

namespace mako\toolbar\console\commands;

use mako\cli\input\arguments\Argument;
use mako\cli\input\arguments\NamedArgument;
use mako\cli\input\Input;
use mako\cli\output\components\Alert;
use mako\cli\output\Output;
use mako\cli\signals\SignalHandler;
use mako\reactor\attributes\CommandArguments;
use mako\reactor\attributes\CommandDescription;
use mako\reactor\Command;
use mako\security\Signer;
use mako\utility\Humanizer;
use RuntimeException;

use function in_array;
use function json_decode;
use function round;
use function socket_accept;
use function socket_bind;
use function socket_clear_error;
use function socket_close;
use function socket_create;
use function socket_last_error;
use function socket_listen;
use function socket_read;
use function socket_select;
use function socket_set_block;
use function socket_set_nonblock;
use function socket_set_option;
use function socket_strerror;
use function sprintf;
use function strtoupper;
use function trim;

#[CommandDescription('Starts the debug server.')]
#[CommandArguments(
	new NamedArgument('address', 'a', 'Address to run the server on', Argument::IS_OPTIONAL),
	new NamedArgument('port', 'p', 'Port to run the server on', Argument::IS_OPTIONAL | Argument::IS_INT),
	new NamedArgument('verbose', 'v', 'Enable verbose output for all information', Argument::IS_BOOL | Argument::IS_OPTIONAL),
	new NamedArgument('request', 'r', 'Enable verbose output for request information', Argument::IS_BOOL | Argument::IS_OPTIONAL),
	new NamedArgument('response', 'R', 'Enable verbose output for response information', Argument::IS_BOOL | Argument::IS_OPTIONAL),
	new NamedArgument('performance', 'P', 'Enable verbose output for performance information', Argument::IS_BOOL | Argument::IS_OPTIONAL)
)]
/**
 * Debug server.
 */
class DebugServer extends Command
{
	/**
	 * Should we keep listening?
	 */
	protected bool $loop = true;

	/**
	 * {@inheritDoc}
	 */
	public function __construct(
		Input $input,
		Output $output,
		protected SignalHandler $signalHandler,
		protected Signer $signer,
		protected ?Humanizer $humanizer
	) {
		parent::__construct($input, $output);

		$this->signalHandler->addHandler([SIGINT, SIGTERM], function (): void {
			$this->loop = false;
		});
	}

	/**
	 * Formats the memory usage to a human friendly format.
	 */
	protected function formatMemoryUsage(int $bytes): string
	{
		return $this->humanizer ? $this->humanizer->fileSize($bytes) : "{$bytes} bytes";
	}

	/**
	 * Determines which alert template we should use.
	 */
	protected function determineAlertTemplate(int $status): string
	{
		if ($status >= 500 && $status <= 599) {
			return Alert::DANGER;
		}
		if ($status >= 400 && $status <= 499) {
			return Alert::WARNING;
		}
		elseif ($status >= 100 && $status <= 199) {
			return Alert::INFO;
		}

		return Alert::SUCCESS;
	}

	/**
	 * Outputs information about the latest request.
	 */
	protected function outputRequestInfo(string $requestInfo, bool $verbose, bool $request, bool $response, bool $performance): void
	{
		$requestInfo = $this->signer->validate($requestInfo);

		if ($requestInfo === false) {
			$this->write('<red>Unable to verify the data that was received.</red>');
			$this->nl();

			return;
		}

		$info = json_decode(trim($requestInfo), associative: true, flags: JSON_THROW_ON_ERROR);

		if ($info['exception'] === null) {
			$this->alert(
				sprintf(
					'<bg_white><black> %s </black></bg_white> <bold>%s</bold> <bold>%s</bold>',
					$info['response']['code'],
					strtoupper($info['request']['method']),
					$info['request']['path']
				),
				$this->determineAlertTemplate($info['response']['code'])
			);

			$this->nl();

			$this->write('<bold>Request</bold>');

			$this->nl();

			$this->labelsAndValues([
				'Route' => $info['request']['route'],
				'Content type' => $info['request']['type'] ?: 'Undefined',
				'Client IP' => $info['request']['ip'],
			], widthPercent: 25.0);

			$this->nl();

			$this->write('<bold>Response</bold>');

			$this->nl();

			$this->labelsAndValues([
				'Content type' => $info['response']['type'],
				'Character set' => $info['response']['charset'],
			], widthPercent: 25.0);

			$this->nl();

			$this->write('<bold>Performance</bold>');

			$this->nl();

			$this->labelsAndValues([
				'Execution time' => round($info['performance']['executionTime'], 4) . ' seconds',
				'Peak memory usage' => $this->formatMemoryUsage($info['performance']['peakMemoryUsage']),
			], 25.0);

			$this->nl();
		}
		else {
			$this->alert(
				sprintf(
					'<bg_white><black> - </black></bg_white> <bold>%s</bold> <bold>%s</bold>',
					strtoupper($info['request']['method']),
					$info['request']['path']
				),
				Alert::DANGER
			);

			$this->nl();
			$this->write("<red>{$info['exception']['name']}: {$info['exception']['message']}</red>");
			$this->nl();
		}
	}

	/**
	 * Executes the command.
	 */
	public function execute(
		string $address = 'localhost',
		int $port = 9000,
		bool $verbose = false,
		bool $request = false,
		bool $response = false,
		bool $performance = false
	): void {
		$server = socket_create(AF_INET, SOCK_STREAM, SOL_TCP);

		socket_set_option($server, SOL_SOCKET, SO_REUSEADDR, value: 1);
		socket_bind($server, $address, $port);
		socket_listen($server);
		socket_set_nonblock($server);

		$message  = 'The <green>Mako</green> debug server is listening for data on ';
		$message .= "<underlined>$address:$port</underlined> ";
		$message .= '<yellow>(ctrl+c to stop)</yellow>';

		$this->nl();
		$this->write($message);
		$this->nl();

		while ($this->loop) {
			$read = [$server];
			$write = $except = null;

			$changed = @socket_select($read, $write, $except, seconds: 1);

			if ($changed === false) {
				$error = socket_last_error();

				if ($error === SOCKET_EINTR) {
					socket_clear_error($server);
					continue;
				}

				throw new RuntimeException(socket_strerror($error), $error);
			}

			if (in_array($server, $read, true)) {
				$client = socket_accept($server);

				if ($client) {
					socket_set_block($client);

					$message = '';

					while (true) {
						$chunk = socket_read($client, 4096);

						if ($chunk === false || $chunk === '') {
							break;
						}

						$message .= $chunk;
					}

					if ($message !== '') {
						$this->outputRequestInfo($message, $verbose, $request, $response, $performance);
					}

					socket_close($client);
				}
			}
		}

		socket_close($server);

		$this->nl();
		$this->nl();
		$this->write('Goodbye! 👋');
		$this->nl();
	}
}
