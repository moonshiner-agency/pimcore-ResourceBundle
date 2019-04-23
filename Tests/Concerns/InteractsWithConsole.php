<?php

namespace Moonshiner\ResourceBundle\Tests\Concerns;

use Symfony\Bundle\FrameworkBundle\Console\Application;
use Symfony\Component\Console\Input\ArrayInput;
use Symfony\Component\Console\Output\ConsoleOutput;

trait InteractsWithConsole
{
    public function console($command, $parameters = [])
    {
        $kernel = $kernel = static::bootKernel();
        $application = new Application($kernel);
        $application->setAutoExit(false);

        $input = new ArrayInput([
            'command' => $command,
            $parameters,
            '-q' => true,
         ]);

        $output = new  ConsoleOutput();
        $application->run($input, $output);

    }
}
