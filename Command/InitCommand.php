<?php

namespace  Moonshiner\BrigthenBundle\Command;

use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Moonshiner\BrigthenBundle\Concerns\InteractsWithFactory;
use Moonshiner\BrigthenBundle\Concerns\InteractsWithDatabase;
use Pimcore\Model\DataObject\AbstractObject;
use Pimcore\Model\Document\Page;
use Pimcore\Model\User;

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

        $command = $this->getApplication()->find('pimcore:definition:import:class');

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


}
