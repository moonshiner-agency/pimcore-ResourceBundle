<?php

namespace Moonshiner\BrigthenBundle\Tests;

use Pimcore\Test\WebTestCase;
use Moonshiner\BrigthenBundle\Concerns\MakesHttpRequests;
use Moonshiner\BrigthenBundle\Concerns\InteractsWithDatabase;
use Moonshiner\BrigthenBundle\Concerns\InteractsWithConsole;
use Moonshiner\BrigthenBundle\Concerns\InteractsWithFactory;

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
        $this->setupFactories();
    }

    public static function setUpBeforeClass()/* The :void return type declaration that should be here would cause a BC issue */
    {
        \Pimcore::setKernel(self::createKernel());
        static::bootKernel();
    }
}
