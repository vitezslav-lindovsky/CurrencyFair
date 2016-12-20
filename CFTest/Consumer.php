<?php
namespace CFTest;

use Exception;


class Consumer
{
	/**@var string */
	private $json = '';

	/**@var StorageInterface */
	private $storage = null;


	public function __construct(StorageInterface $storage)
	{
		$this->storage = $storage;
	}


	public function setJson($json)
	{
		$this->json = $json;
		$this->validateJson();
		$this->save();
	}


	protected function validateJson()
	{
		if (empty($this->json))
		{
			throw new Exception('No data');
		}

		if ($this->checkJson() === false)
		{
			throw new Exception('Invalid JSON');
		}
	}


	/**
	 * Can contain check if we have all needed data, etc
	 */
	protected function checkJson()
	{
		$dataArray = json_decode($this->json, true);

		return is_array($dataArray);
	}


	protected function save()
	{
		$save = $this->storage->saveItem($this->json);

		if ($save === false)
		{
			throw new Exception("Can't save data");
		}
	}

}
