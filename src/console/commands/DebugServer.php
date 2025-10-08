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
use Monolog\Level;
use RuntimeException;

use function count;
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
	new NamedArgument('filter', 'f', 'Filter incomming requests', Argument::IS_OPTIONAL),
	new NamedArgument('verbose', 'v', 'Enable verbose output for all information', Argument::IS_BOOL | Argument::IS_OPTIONAL)
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
		protected Humanizer $humanizer
	) {
		parent::__construct($input, $output);

		$this->signalHandler->addHandler([SIGINT, SIGTERM], function (): void {
			$this->loop = false;
		});
	}

	/**
	 * Returns TRUE if the provided filter is a regex pattern and FALSE if not.
	 */
	protected function validateFilter(string $filter): bool
	{
		if (@preg_match("#{$filter}#u", '') === false) {
			return false;
		}

		return true;
	}

	/**
	 * Escapes the output.
	 */
	protected function escape(string $string): string
	{
		if ($this->output->formatter !== null) {
			return $this->output->formatter->escape($string);
		}

		return $string;
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
	 * Output request information.
	 */
	protected function outputRequestInformation(array $request): void
	{
		$this->nl();
		$this->write('<bold>Request</bold>');
		$this->nl();

		$this->labelsAndValues([
			'Route' => $this->escape($request['route']),
			'Content type' => $this->escape($request['type'] ?: 'Undefined'),
			'Client IP' => $request['ip'],
		], widthPercent: 25.0);
	}

	/**
	 * Output response information.
	 */
	protected function outputResponseInformation(array $response): void
	{
		$this->nl();
		$this->write('<bold>Response</bold>');
		$this->nl();

		$this->labelsAndValues([
			'Content type' => $response['type'],
			'Character set' => $response['charset'],
		], widthPercent: 25.0);
	}

	/**
	 * Output performance information.
	 */
	protected function outputPerformanceInformation(array $performance, bool $verbose): void
	{
		$this->nl();
		$this->write('<bold>Performance</bold>');
		$this->nl();

		$this->labelsAndValues([
			'Execution time' => round($performance['executionTime'], 4) . ' seconds',
			'Peak memory usage' => $this->humanizer->fileSize($performance['peakMemoryUsage']),
			'Database queries' => count($performance['queries']),
		], 25.0);

		if ($verbose && !empty($performance['queries'])) {
			foreach ($performance['queries'] as $key => $query) {
				$time = round($query['time'], 4);

				$this->nl();
				$this->frame($this->escape($query['query']), "{$time} seconds");
			}
		}
	}

	/**
	 * Decorates the log level.
	 *
	 * @param value-of<Level::VALUES> $logLevel
	 */
	protected function decorateLogLevel(int $logLevel): string
	{
		$level = Level::fromValue($logLevel);

		return sprintf(match ($level) {
			Level::Debug => '<bg_green><black> %s </black></bg_green>',
			Level::Info => '<bg_blue><black> %s </black></bg_blue>',
			Level::Notice, Level::Warning => '<bg_yellow><black> %s </black></bg_yellow>',
			Level::Error, Level::Critical, Level::Alert, Level::Emergency => '<bg_red><white> %s </white></bg_red>',
		}, $level->getName());
	}

	/**
	 * Output exception information.
	 */
	protected function outputExceptionInformation(array $exception): void
	{
		$this->nl();
		$this->write("<bold>{$exception['name']}</bold>");
		$this->nl();
		$this->write($this->escape($exception['message']));
		$this->nl();
		$this->write('<faded>Check the application error log for more details.</faded>');
	}

	/**
	 * Outputs log entries.
	 */
	protected function outputLogEntries(array $logEntries): void
	{
		$this->nl();
		$this->write('<bold>Log entries</bold>');

		foreach ($logEntries as $logEntry) {
			$level = $this->decorateLogLevel($logEntry['level']);
			$message = $this->escape($logEntry['message']);
			$this->nl();
			$this->write("<bold>{$level}</bold> <faded>{$logEntry['time']}</faded> {$message}");
		}
	}

	/**
	 * Outputs information about the latest request.
	 */
	protected function output(string $requestInfo, ?string $filter, bool $verbose): void
	{
		// Validate and decode data

		$requestInfo = $this->signer->validate($requestInfo);

		if ($requestInfo === false) {
			$this->nl();
			$this->write('<red>Unable to verify the data that was received.</red>');
			$this->nl();

			return;
		}

		$info = json_decode(trim($requestInfo), associative: true, flags: JSON_THROW_ON_ERROR);

		// Check if we should display the request data

		if ($filter !== null && preg_match("#{$filter}#u", "{$info['request']['path']}{$info['request']['queryString']}") !== 1) {
			return;
		}

		// Display request information

		if ($info['exception'] === null) {
			$this->alert(
				sprintf(
					'<bg_white><black> %s </black></bg_white> <bold>%s</bold> <bold>%s</bold>%s',
					$info['response']['code'],
					strtoupper($info['request']['method']),
					$this->escape($info['request']['path']),
					$info['request']['queryString'] ? $this->escape("?{$info['request']['queryString']}") : ''
				),
				$this->determineAlertTemplate($info['response']['code'])
			);

			$this->outputRequestInformation($info['request']);
			$this->outputResponseInformation($info['response']);
			$this->outputPerformanceInformation($info['performance'], $verbose);
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

			$this->outputExceptionInformation($info['exception']);
		}

		// Output log entries

		if (!empty($info['log'])) {
			$this->outputLogEntries($info['log']);
		}

		$this->nl();
	}

	/**
	 * Executes the command.
	 */
	public function execute(string $address = 'localhost', int $port = 9000, ?string $filter = null, bool $verbose = false): void
	{
		$server = socket_create(AF_INET, SOCK_STREAM, SOL_TCP);

		socket_set_option($server, SOL_SOCKET, SO_REUSEADDR, value: 1);
		socket_bind($server, $address, $port);
		socket_listen($server);
		socket_set_nonblock($server);

		$message  = 'The <green>Mako</green> debug server is listening for data on ';
		$message .= "<underlined>{$address}:{$port}</underlined> ";
		$message .= '<yellow>(ctrl+c to stop)</yellow>';

		if (!empty($filter)) {
			if (!$this->validateFilter($filter)) {
				$this->nl();
				$this->error('<red>The provided filter is not a valid regex pattern.</red>');
				$this->nl();
				return;
			}

			$message .= PHP_EOL . PHP_EOL;
			$message .= "The server will only output info for requests matching the <blue>'{$this->escape($filter)}'</blue> pattern.";
		}

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
						$this->output($message, $filter, $verbose);
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
