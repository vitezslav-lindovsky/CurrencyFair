<?php

namespace CFTest;

use Exception;
use Redis;


class StorageRedis implements StorageInterface
{
	/** @var string Redis queue name */
	private $queueName = '';

	/** @var Redis */
	private $redis = null;


	public function __construct($configuration)
	{
		$this->connect($configuration);
		$this->queueName = $configuration['queue_name'];
	}


	public function saveItem($data)
	{
		$id = $this->redis->lPush($this->queueName, $data);

		return (bool) $id;
	}


	public function retrieveItem()
	{
		$item = $this->redis->rPop($this->queueName);

		return $item;
	}


	protected function connect($configuration)
	{
		$haveConfig = (isset($configuration['server']) && isset($configuration['server']));

		if ($haveConfig == false)
		{
			throw new Exception("Can't connect to Redis server");
		}

		$this->redis = new Redis();
		$this->redis->connect($configuration['server'], $configuration['port']);
	}

}
