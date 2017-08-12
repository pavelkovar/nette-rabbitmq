<?php

declare(strict_types=1);

namespace Gamee\RabbitMQ\Console;

class ConsoleParameters
{
	/**
	 * @var array
	 */
	private $array;


	public function __construct(array $array)
	{
		$this->array = $array;
	}


	public function getByKey(string $key, $default = null)
	{
		return $this->hasKey($key) ? $this->array[$key] : $default;
	}


	public function hasKey(string $key): bool
	{
		return array_key_exists($key, $this->array);
	}

}