<?php

use Symfony\Component\Yaml\Yaml;

require __DIR__ . '/vendor/autoload.php';

$yaml = Yaml::parse(file_get_contents('dbpop.yml'));

$config = new \Doctrine\DBAL\Configuration();

$connectionParams = array(
    'dbname' => $yaml['db']['nameDataBase'],
    'user' => $yaml['db']['user'],
    'password' => $yaml['db']['password'],
    'host' => $yaml['db']['server'],
    'driver' => $yaml['db']['driver'],
    'charset' => $yaml['db']['charset'],
);

try {
	$conn = \Doctrine\DBAL\DriverManager::getConnection($connectionParams, $config);
	$conn->connect();
} catch (Exception $e) {
	echo $e->getMessage();
	exit();
}
