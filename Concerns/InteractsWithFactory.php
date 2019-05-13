<?php

namespace Moonshiner\BrigthenBundle\Concerns;

trait InteractsWithFactory
{
    protected $factory;

    public function setupFactories()
    {
        $this->factory = \Moonshiner\BrigthenBundle\Services\Factory::getInstance();
    }

}
