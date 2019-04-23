<?php

namespace Moonshiner\ResourceBundle\Tests;

use Pimcore\Test\WebTestCase;
use Moonshiner\ResourceBundle\Tests\Concerns\MakesHttpRequests;
use Moonshiner\ResourceBundle\Tests\Concerns\InteractsWithDatabase;
use Moonshiner\ResourceBundle\Tests\Concerns\InteractsWithConsole;
use Moonshiner\ResourceBundle\Tests\Concerns\InteractsWithFactory;

abstract class TestCase extends WebTestCase
{
    use MakesHttpRequests;
    use InteractsWithConsole;
    use InteractsWithDatabase;
    use InteractsWithFactory;

     /**
     * Setup the test environment.
     *
     * @return void
     */
    protected function setUp()
    {
        \Pimcore::setKernel(self::createKernel());
        \Pimcore::getKernel()->boot();
        InteractsWithDatabase::setupPimcore();
        $this->setupFactories();
    }
}
