<?php

namespace PPI\PapiInfo\Console\Command;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

use PPI\PapiInfo\Compile;


class CompileCommand extends Command
{
    protected function configure()
    {
        $this
            ->setName('info:compile')
            ->setDescription('Builds all the data and returns it.')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $compile = new Compile;

        $d = $compile->getAllData();

        echo json_encode($d, JSON_PRETTY_PRINT);

    }

    
        
}