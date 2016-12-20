<?php
error_reporting(E_ALL);
ini_set('html_errors', 'off');

include __DIR__ . '/vendor/autoload.php';

use CFTest\Database;
use CFTest\Processor;
use CFTest\StorageFactory;

try {
	$dsn = 'mysql:host=localhost;dbname=cftest';
	$pdoAttributes = [PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES 'UTF8'", PDO::ATTR_EMULATE_PREPARES => false];
	$pdo = new Pdo($dsn, 'cftest', 'cftest', $pdoAttributes);
	$database = new Database($pdo);

	$storage = StorageFactory::create('redis', ['server' => '127.0.0.1', 'port' => '6379']);

	$consumer = new Processor($storage, $database);
	$consumer->processItem();

} catch (\Exception $e) {
	die($e->getMessage());
}

die('ok');
