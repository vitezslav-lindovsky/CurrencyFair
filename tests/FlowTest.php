<?php
namespace CFTest;

use PDO;
use PHPUnit_Framework_TestCase;

class FlowTest extends PHPUnit_Framework_TestCase
{
	private $currencies = ['EUR', 'GBP', 'CZK', 'PHP'];
	private $countries  = ['FR', 'IE', 'CZ', 'DE'];
	private $data = [];


	public function testFlow()
	{
		$dsn = 'mysql:host=localhost;dbname=cftest';
		$pdoAttributes = [PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES 'UTF8'", PDO::ATTR_EMULATE_PREPARES => false]; // TODO check
		$pdo = new Pdo($dsn, 'cftest', 'cftest', $pdoAttributes);
		$database = new Database($pdo);

		$storage = StorageFactory::create('redis', ['server' => '127.0.0.1', 'port' => '6379', 'queue_name' => 'cftest-testing']);

		$consumer  = new Consumer($storage);
		$processor = new Processor($storage, $database);

		for ($i = rand(1, 100); $i > 0; $i--)
		{
			$data = $this->createRandomData();
			$this->data[] = $data;
			$consumer->setJson(json_encode($data));
		}

		$processor->processItem();

		$dataSaved = $database->getLastResults(count($this->data));

		foreach ($this->data as $item)
		{
			$itemSaved = array_pop($dataSaved);

			$this->assertEquals($item['userId'], $itemSaved['user_id']);
			$this->assertEquals($item['currencyFrom'], $itemSaved['currency_from']);
			$this->assertEquals($item['currencyTo'], $itemSaved['currency_to']);
			$this->assertEquals($item['amountSell'], $itemSaved['amount_sell']);
			$this->assertEquals($item['amountBuy'], $itemSaved['amount_buy']);
			$this->assertEquals($item['rate'], $itemSaved['rate']);
			// $this->assertEquals($item['timePlaced'], $itemSaved['time_placed']); // TODO
			$this->assertEquals($item['originatingCountry'], $itemSaved['originating_country']);
		}

	}


	protected function createRandomData()
	{
		$currencies = $this->currencies;
		shuffle($currencies);

		$data =
		[
			'userId' => mt_rand(42, 1764),
			'currencyFrom' => array_shift($currencies),
			'currencyTo' => array_shift($currencies),
			'amountSell' => (float) (mt_rand(101, 20000) / 100),
			'amountBuy'  => (float) (mt_rand(101, 20000) / 100),
			'rate' => (float) (mt_rand(1, 10) / 10),
			'timePlaced' => date('d-m-Y H:I:s'), // '24-JAN-15 10:27:44', // TODO
			'originatingCountry' => $this->countries[array_rand($this->countries)],
		];

		return $data;
	}

}
