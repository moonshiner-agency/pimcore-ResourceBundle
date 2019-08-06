<?php

namespace  Moonshiner\BrigthenBundle\Command;

use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputInterface;
use Moonshiner\BrigthenBundle\Concerns\InteractsWithConsole;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Moonshiner\BrigthenBundle\Concerns\InteractsWithFactory;
use Moonshiner\BrigthenBundle\Concerns\InteractsWithDatabase;
use Symfony\Component\Console\Input\ArrayInput;

use Pimcore\Model\Document\Page;

class SetupTestCommand extends ContainerAwareCommand
{
    use InteractsWithDatabase;
    use InteractsWithConsole;

    protected function configure()
    {
        $this
            ->setName('moonshiner:setup-test-db')
            ->setDescription('Drop and creates the TEST database');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $kernel = \Pimcore\Bootstrap::kernel();
        \Pimcore::setKernel($kernel);
        InteractsWithDatabase::refresh();
        InteractsWithDatabase::setupPimcore($db = 'test');
        InteractsWithConsole::runCommand('pimcore:deployment:classes-rebuild', ['-c' => true ]);

        $output->writeln('Enjoy your fresh db!');
    }
}
