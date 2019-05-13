<?php

namespace Moonshiner\BrigthenBundle\Concerns;

trait InteractsWithKernel
{
    protected $kernel;

    public function setupFactories()
    {
        $kernel = static::bootKernel();
    }

}
