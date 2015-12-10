<?php

if(array_key_exists('CLEARDB_DATABASE_URL', $_ENV)) {

	// Using ClearDB for mysql database
	$url = parse_url($_ENV['CLEARDB_DATABASE_URL']); 
	$container->setParameter('database_driver', 'pdo_mysql'); 
	$container->setParameter('database_host',      $url['host']); 
	$container->setParameter('database_port', '~');
	$container->setParameter('database_name', trim($url['path'], '/')); 
	$container->setParameter('database_user',      $url['user']); 
	$container->setParameter('database_password',  $url['pass']);
}

if(array_key_exists('SWIFTMAILER_URL', $_ENV)) {

	$url = parse_url($_ENV['SWIFTMAILER_URL']); 
	$container->setParameter('mailer_transport', $url['scheme']); 
	$container->setParameter('mailer_host',      $url['host']);  
	$container->setParameter('mailer_user',      $url['user']); 
	$container->setParameter('mailer_password',  $url['pass']);
}

?>
