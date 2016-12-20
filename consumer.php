<?php
error_reporting(E_ALL);
ini_set('html_errors', 'off');

include __DIR__ . '/vendor/autoload.php';

use CFTest\Consumer;
use CFTest\StorageFactory;


$json = file_get_contents('php://input');

try {
	$consumerStorage = StorageFactory::create('redis', ['server' => '127.0.0.1', 'port' => '6379']);

	$consumer = new Consumer($consumerStorage);
	$consumer->setJson($json);

} catch (\Exception $e) {
	response($e->getMessage());
}

response();



function response($errorMessage = null)
{
	$return = ['status' => 'OK'];

	if ($errorMessage !== null)
	{
		$return = ['status' => 'error', 'message' => $errorMessage];
		header('HTTP/1.1 400 Bad Request');
	}

	die(json_encode($return));
}
