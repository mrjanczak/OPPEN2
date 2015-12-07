<?php

// Using ClearDB for mysql database
$db = parse_url($_ENV['CLEARDB_DATABASE_URL']); 
$container->setParameter('database_driver', 'pdo_mysql'); 
$container->setParameter('database_host', $db['host']); 
$container->setParameter('database_port', '~');
$container->setParameter('database_name', trim($db['path'], '/')); 
$container->setParameter('database_user', $db['user']); 
$container->setParameter('database_password', $db['pass']);

// Using Mandrill to send email
/*
$container->setParameter('mailer_transport', 'smtp');
$container->setParameter('mailer_host', 'smtp.mandrillapp.com');
$container->setParameter('mailer_user', $_ENV['MANDRILL_USERNAME']);
$container->setParameter('mailer_password', $_ENV['MANDRILL_APIKEY']);
*/

// Other settings
$container->setParameter('locale', 'pl');
$container->setParameter('secret', 'c5bd9c14be810cd74439e07bdbc933049e092c5c');

?>
