<?php

declare(strict_types=1);

/**
 * @copyright   Copyright (c) 2017 gameeapp.com <hello@gameeapp.com>
 * @author      Pavel Janda <pavel@gameeapp.com>
 * @package     Gamee
 */

namespace Gamee\RabbitMQ\Console\Command;

use Gamee\RabbitMQ\Console\ConsoleParameters;
use Gamee\RabbitMQ\Consumer\ConsumerFactory;
use Gamee\RabbitMQ\Consumer\ConsumersDataBag;
use Gamee\RabbitMQ\Consumer\Exception\ConsumerFactoryException;
use Symfony\Component\Console\Command\Command;

abstract class AbstractConsumerCommand extends Command
{

	/**
	 * @var ConsumersDataBag
	 */
	protected $consumersDataBag;

	/**
	 * @var ConsumerFactory
	 */
	protected $consumerFactory;


	public function __construct(ConsumersDataBag $consumersDataBag, ConsumerFactory $consumerFactory)
	{
		parent::__construct();

		$this->consumersDataBag = $consumersDataBag;
		$this->consumerFactory = $consumerFactory;
	}


	/**
	 * @throws \InvalidArgumentException
	 */
	protected function validateConsumerName(string $consumerName): void
	{
		try {
			$this->consumerFactory->getConsumer($consumerName);

		} catch (ConsumerFactoryException $e) {
			throw new \InvalidArgumentException(
				sprintf(
					"Consumer [$consumerName] does not exist. \n\n Available consumers: %s",
					implode('', array_map(function($s) {
						return "\n\t- [{$s}]";
					}, $this->consumersDataBag->getDatakeys()))
				)
			);
		}
	}


	/**
	 * @throws \InvalidArgumentException
	 */
	protected function processConsoleParameters(array $params): ConsoleParameters
	{
		$map = [];
		foreach ($params as $param) {
			$delimiterPos = strpos($param, '=');
			if ($delimiterPos === false) {
				throw new \InvalidArgumentException("Optional parameter [$param] has invalid format. \n\n Should be key=value.");
			}

			$key = substr($param, 0, $delimiterPos);
			$value = substr($param, $delimiterPos + 1);

			$map[$key] = $value;
		}
		return new ConsoleParameters($map);
	}

}
