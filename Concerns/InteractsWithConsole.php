<?php

namespace Moonshiner\BrigthenBundle\Concerns;

use Symfony\Bundle\FrameworkBundle\Console\Application;
use Symfony\Component\Console\Input\ArrayInput;
use Symfony\Component\Console\Output\ConsoleOutput;

trait InteractsWithConsole
{
    public function console($command, $parameters = [], $application = null)
    {
        if (! $application) {
            if (! $kernel = $this->kernel) {
                $kernel = \Pimcore::getKernel();
            }
            $application = new Application($kernel);
        }

        $application->setAutoExit(false);
        $input = new ArrayInput(array_merge([
            'command' => $command,
            '-q' => true,
        ], $parameters));

        $output = new  ConsoleOutput();
        $application->run($input, $output);
    }
}
