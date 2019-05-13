<?php

namespace Moonshiner\BrigthenBundle\Concerns;

trait InteractsWithKernel
{
    protected $kernel;

    public function bootKernel()
    {
        $this->kernel = static::bootKernel();
    }

}
