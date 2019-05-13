<?php

namespace Moonshiner\BrigthenBundle\Tests;

use Pimcore\Test\WebTestCase;
use Moonshiner\BrigthenBundle\Concerns\MakesHttpRequests;
use Moonshiner\BrigthenBundle\Concerns\InteractsWithDatabase;
use Moonshiner\BrigthenBundle\Concerns\InteractsWithConsole;
use Moonshiner\BrigthenBundle\Concerns\InteractsWithFactory;
use Moonshiner\BrigthenBundle\Concerns\InteractsWithKernel;

abstract class TestCase extends WebTestCase
{
    use MakesHttpRequests;
    use InteractsWithConsole;
    use InteractsWithDatabase;
    use InteractsWithFactory;
    use InteractsWithKernel;

     /**
     * Setup the test environment.
     *
     * @return void
     */
    protected function setUp()
    {
        \Pimcore::setKernel(self::createKernel());
        $this->bootKernel();
        InteractsWithDatabase::setupPimcore();
        $this->setupFactories();
    }
}
