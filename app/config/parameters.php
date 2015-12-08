<?php

if(array_key_exists('CLEARDB_DATABASE_URL', $_ENV)) {

	// Using ClearDB for mysql database
	$db = parse_url($_ENV['CLEARDB_DATABASE_URL']); 
	$container->setParameter('database_driver', 'pdo_mysql'); 
	$container->setParameter('database_host', $db['host']); 
	$container->setParameter('database_port', '~');
	$container->setParameter('database_name', trim($db['path'], '/')); 
	$container->setParameter('database_user', $db['user']); 
	$container->setParameter('database_password', $db['pass']);
}

?>
