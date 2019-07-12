<?php

namespace Moonshiner\BrigthenBundle;

use Moonshiner\BrigthenBundle\DependencyInjection\ResourceExtension;
use Symfony\Component\HttpKernel\Bundle\Bundle;

class BrigthenBundle extends Bundle
{
    public function getContainerExtension()
    {
        return new ResourceExtension();
    }
}
