<?php
namespace CFTest;


class StorageFile implements StorageInterface
{

	public function saveItem($data)
	{
		if (empty($data))
		{
			return false;
		}

		$saved = file_put_contents('/tmp/cf/' . str_replace('.', '_', microtime(true)) . '.json', $data);

		return (bool) $saved; // will return false even if data = ''.
	}


	public function retrieveItem()
	{
		// TODO: Implement retrieveItem() method.
	}
}
