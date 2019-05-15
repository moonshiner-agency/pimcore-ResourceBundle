<?php

namespace  Moonshiner\BrigthenBundle\Command;

use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Moonshiner\BrigthenBundle\Concerns\InteractsWithFactory;
use Moonshiner\BrigthenBundle\Concerns\InteractsWithDatabase;
use Symfony\Component\Console\Input\ArrayInput;
use Pimcore\Model\Document\Page;

class InitCommand extends ContainerAwareCommand
{
    use InteractsWithFactory;
    use InteractsWithDatabase;

    protected function configure()
    {
        $this
            ->setName('moonshiner:init')
            ->setDescription('initial setup for pimcore projects')
            ->addOption(
                'fresh',
                'f',
                InputOption::VALUE_NONE,
                'clear the database and fresh seed it.'
            )
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {

        $this->setupFactories();
        $this->factory->load('./var/factories');

        $this->refresh( $input );
        $this->seedPageFolders();
        $this->seedCustomer($output);

        $output->writeln('Done buddy!');
    }

    protected function refresh($input)
    {
        if ($input->getOption('fresh')) {
            InteractsWithDatabase::refresh();
            InteractsWithDatabase::setupPimcore($db = 'pimcore');
        }
    }

    protected function seedPageFolders()
    {
        try {

            $api = $this->factory->of(Page::class)->states(['folder'])->create([
                'name' => 'api',
                'key' => 'api',
                'parentId' => 1
            ]);

            $pages = $this->factory->of(Page::class)->states(['folder'])->create([
                'name' => 'pages',
                'key' => 'pages',
                'parentId' => $api->getId()
            ]);

            $this->factory->of(Page::class)->create([
                'name' => 'EN',
                'key' => 'en',
                'parentId' => $pages->getId()
            ]);

            $this->factory->of(Page::class)->create([
                'name' => 'DE',
                'key' => 'de',
                'parentId' => $pages->getId()
            ]);

        } catch (\Throwable $th) {  }
    }

    protected function seedCustomer(OutputInterface $output)
    {
        $customerDefinitionPath = "vendor/moonshiner/brigthenbundle/Moonshiner/BrigthenBundle/var/classes/class_Customer_export.json";
        $command = $this->getApplication()->find('pimcore:definition:import:class');
        $arguments = [
            'command' => 'pimcore:definition:import:class ',
            'path' =>  $customerDefinitionPath
        ];

        $command->run(new ArrayInput($arguments), $output);
    }


}
