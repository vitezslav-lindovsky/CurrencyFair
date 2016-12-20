<?php
namespace CFTest;

use Exception;


class Processor
{
	/**@var StorageInterface */
	private $storage = null;

	/**@var Database */
	private $database = null;


	private $fromTo =
	[
		'userId' => 'user_id',
		'currencyFrom' => 'currency_from',
		'currencyTo' => 'currency_to',
		'amountSell' => 'amount_sell',
		'amountBuy' => 'amount_buy',
		'rate' => 'rate',
		'timePlaced' => 'time_placed',
		'originatingCountry' => 'originating_country',
	];


	public function __construct(StorageInterface $storage, Database $database)
	{
		$this->storage = $storage;
		$this->database = $database;
	}


	public function processItem()
	{
		while ($json = $this->storage->retrieveItem())
		{
			try {
				$data = $this->parseJson($json);
				$this->saveToDb($data);
			} catch (Exception $e) {
				// TODO log
			}
		}
	}


	protected function parseJson($json)
	{
		return json_decode($json, true);
	}


	protected function saveToDb($data)
	{
		$params = [];

		foreach ($this->fromTo as $from => $to)
		{
			$params[$to] = $data[$from];
		}

		$ids = $this->database->getIds($params);
		unset($params['currency_from'], $params['currency_to'], $params['originating_country']);
		$params = array_merge($params, $ids);

		$this->database->saveMessage($params);
	}


}
