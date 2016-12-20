<?php
namespace CFTest;

/**
 * ConsumerStorage contains storage implementation.
 */

interface StorageInterface
{
	/**
	 * @param $item string
	 * @return boolean Success
	 */
	public function saveItem($item);


	/**
	 * @return string
	 */
	public function retrieveItem();
}
