<?php

define('PIMCORE_TEST', true);
define('PIMCORE_CLASS_DIRECTORY', __DIR__ . '/tmp/var/classes');

require './vendor/autoload.php';

\Pimcore\Bootstrap::setProjectRoot();
\Pimcore\Bootstrap::boostrap();

// load factories
$factory = \Moonshiner\BrigthenBundle\Services\Factory::getInstance();
$path = './var/factories';

if (is_dir($path)) {
    foreach ( \Symfony\Component\Finder\Finder::create()->files()->name('*.php')->in($path) as $file) {
        require $file->getRealPath();
    }
}