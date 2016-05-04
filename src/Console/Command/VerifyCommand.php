<?php

namespace Pirates\PapiInfo\Console\Command;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

use Pirates\PapiInfo\Verify;

class VerifyCommand extends Command
{
    protected function configure()
    {
        $this
            ->setName('info:verify')
            ->setDescription('Verifies integrity of the data')
            ->addOption(
                'error-codes-only',
                null,
                InputOption::VALUE_NONE,
                'If set, the task will only output an error code.'
            )
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $this->output = $output;
        $this->noOutput = $input->getOption('error-codes-only');

        $this->log('Running data verification.');

        $rootPath = __DIR__ . '/../../..';

        $dataFiles = scandir($rootPath . '/data/');

        $numFiles = 0;
        $numErrors = 0;

        $testers = get_class_methods('Pirates\PapiInfo\Verify');

        foreach ($dataFiles as $filename) {
            if (preg_match('/.+\.json$/i', $filename) === 1) {
                $numFiles++;
                $this->log(sprintf('#%s  Testing %s', $numFiles, $filename));

                $json = file_get_contents(sprintf('%s/data/%s', $rootPath, $filename));
                
                // Test syntax
                if (($emsg = Verify::verifySyntax($json)) !== true) {
                    $numErrors++;
                    $this->log(sprintf(PHP_EOL.'  <error>[%s] Invalid JSON syntax:'.PHP_EOL.' %s</error>' . PHP_EOL, $filename, $emsg));
                    continue;
                }

                $ppData = json_decode($json, true);

                foreach ($testers as $test) {
                    if ($test === 'verifySyntax') {
                        continue;
                    }
                    $result = Verify::$test($ppData);
                    if ($result !== true) {
                        $numErrors++;
                        $this->log(sprintf(PHP_EOL.'  <error>[%s] %s</error>' . PHP_EOL, $filename, $result)); 
                    }
                }

            }
        }

        if ($numErrors !== 0) {
            if ($this->noOutput) {
                exit(100);
            }
            $this->log(sprintf(PHP_EOL.'  <error>%s errors found!</error>', $numErrors));

        } else {
            if ($this->noOutput) {
                exit(0);
            }
            $this->log(PHP_EOL . '<info>No errors found!</info>' );
        }

    }

    public function log($msg) {
        if (!$this->noOutput) {
            $this->output->writeln($msg);
        }
    }

    
        
}