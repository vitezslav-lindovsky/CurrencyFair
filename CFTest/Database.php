<?php
namespace CFTest;


use Exception;
use PDO;
use PDOException;

class Database
{
	public function __construct(PDO $pdo)
	{
		$this->pdo = $pdo;
	}


	/**
	 * @param integer $numberOfResults
	 * @return array
	 */
	public function getLastResults($numberOfResults)
	{
		$sql = "
		SELECT me.user_id, me.amount_sell, me.amount_buy, me.rate, me.time_placed,
			cf.code AS currency_from, ct.code AS currency_to, co.code AS originating_country
		FROM message me
		JOIN currency cf ON (cf.id = me.id_currency_from)
		JOIN currency ct ON (ct.id = me.id_currency_to)
		JOIN country  co ON (co.id = me.id_originating_country)
		ORDER BY me.id DESC
		LIMIT :limit;
		";

		return $this->sql($sql, ['limit' => $numberOfResults])->fetchAll(PDO::FETCH_ASSOC);
	}


	/**
	 * @param $params
	 */
	public function saveMessage($params)
	{
		$sql = "
		INSERT INTO message
		(time_placed, user_id, amount_sell, amount_buy, rate, id_currency_from, id_currency_to, id_originating_country)
		VALUES
		(:time_placed, :user_id, :amount_sell, :amount_buy, :rate, :id_currency_from, :id_currency_to, :id_originating_country)
		 ;";

		$this->sql($sql, $params);
	}


	public function getIds($data)
	{
		$sql = "
		SELECT id FROM currency WHERE code = :currency_from
		UNION ALL
		SELECT id FROM currency WHERE code = :currency_to
		UNION ALL
		SELECT id FROM country  WHERE code = :originating_country
		;";

		$params = array_intersect_key($data, array_flip(['currency_from', 'currency_to', 'originating_country']));

		$ids = $this->sql($sql, $params)->fetchAll(PDO::FETCH_COLUMN);

		if (count($ids) !== count($params))
		{
			throw new Exception("Can't get some IDs");
		}

		return
			[
				'id_currency_from' => $ids[0],
				'id_currency_to' => $ids[1],
				'id_originating_country' => $ids[2],
			];
	}


	protected function sql($query, $params = [])
	{
		$statement = $this->pdo->prepare($query);

		if ($statement === false)
		{
			$err = $query . ' :: sql(prepare) :: ' . $this->pdo->errorInfo()[2];

			throw new PDOException($err);
		}

		if (empty($params) === false)
		{
			$keys = array_keys($params);

			foreach ($keys as $i => $key)
			{
				$keys[$i] = ':' . $key;
			}

			$params = array_combine($keys, $params);
		}

		$exec = $statement->execute($params);

		if ($exec === false)
		{
			$err = $query . ' :: sql(execute) :: ' . $this->pdo->errorInfo()[2];

			throw new PDOException($err);
		}

		return $statement;
	}

}