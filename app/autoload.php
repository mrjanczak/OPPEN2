<?php

use Doctrine\Common\Annotations\AnnotationRegistry;
use Composer\Autoload\ClassLoader;

/**
 * @var ClassLoader $loader
 */
$loader = require __DIR__.'/../vendor/autoload.php';

$loader->add('SensioGeneratorBundle', __DIR__.'/../vendor/sensio/generator-bundle/Sensio/Bundle/GeneratorBundle');

AnnotationRegistry::registerLoader(array($loader, 'loadClass'));

return $loader;
