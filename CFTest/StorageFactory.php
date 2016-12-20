<?php
namespace CFTest;

use Exception;

class StorageFactory
{

	/**
	 * @param $storageType string
	 * @param $configuration array
	 * @return StorageInterface
	 * @throws Exception
	 */
	public static function create($storageType, $configuration = [])
	{
		switch ($storageType)
		{
			case 'file'  :
				$storage = new StorageFile($configuration);
				break;

			case 'redis' :
				$storage = new StorageRedis($configuration);
				break;

			default:
				throw new Exception('Unknown Consumer storage');
		}

		return $storage;
	}

}
