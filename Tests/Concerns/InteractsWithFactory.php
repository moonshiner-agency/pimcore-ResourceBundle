<?php

namespace Moonshiner\ResourceBundle\Tests\Concerns;

use Symfony\Component\Finder\Finder;

trait InteractsWithFactory
{
    protected $factory;

    public function setupFactories()
    {
        static::bootKernel();
        $this->factory = \Moonshiner\ResourceBundle\Services\Factory::getInstance();
    }

}
