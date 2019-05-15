<?php

namespace Moonshiner\BrigthenBundle\Concerns;

use Symfony\Bundle\FrameworkBundle\Console\Application;
use Symfony\Component\Console\Input\ArrayInput;
use Symfony\Component\Console\Output\ConsoleOutput;

trait InteractsWithConsole
{
    public function console($command, $parameters = [])
    {
        if(! $kernel = $this->kernel )
        {
            $kernel = static::bootKernel();
        }
        $application = new Application($kernel);
        $application->setAutoExit(false);

        $input = new ArrayInput( array_merge( $parameters,[
            'command' => $command,
            '-q' => true,
        ]) );

        $output = new  ConsoleOutput();
        $application->run($input, $output);
    }
}
