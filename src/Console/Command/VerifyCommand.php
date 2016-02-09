<?php

namespace Pirates\PapiInfo\Console\Command;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

use Seld\JsonLint\JsonParser;
use Seld\JsonLint\Parsing\Exception;

class VerifyCommand extends Command
{
    protected function configure()
    {
        $this
            ->setName('info:verify')
            ->setDescription('Verifies integrity of the data')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $output->writeln('Running data verification.');

        $parser = new JsonParser();

        $rootPath = __DIR__ . '/../../..';

        $dataFiles = scandir($rootPath . '/data/');

        $numFiles = 0;
        $numErrors = 0;
        foreach ($dataFiles as $filename) {
            if (preg_match('/.+\.json$/i', $filename) === 1) {
                $numFiles++;
                $output->writeln(sprintf('#%s   Testing %s', $numFiles, $filename));

                $json = file_get_contents(sprintf('%s/data/%s', $rootPath, $filename));
                
                try {
                    $return = $parser->parse($json);
                    
                } catch (Parsing\Exception $e) {
                    $numErrors++;
                    $output->writeln(sprintf(PHP_EOL.'  <error>%s</error>' . PHP_EOL, $e->getMessage()));
                    continue;
                }

                $ppData = json_decode($json, true);

                try {
                    $this->dataIntegrityTest($ppData);
                } catch (\Exception $e) {
                    $numErrors++;
                    $output->writeln(sprintf(PHP_EOL.'  <error>%s</error>' . PHP_EOL, $e->getMessage()));
                }

            }
        }

        if ($numErrors !== 0) {
            $output->writeln(sprintf(PHP_EOL.'  <error>%s errors found!</error>', $numErrors));

        } else {
            $output->writeln(PHP_EOL . '<info>No errors found!</info>' );
        }

    }

    function dataIntegrityTest($data) {
        $required = array(
            "countryCode" => false,
            "country"     => false,
            "partyName"   => false,
            "type"        => false,
            "partyCode"   => false
            );

        foreach ($data as $key => $value) {
            switch ($key) {
                case 'countryCode':
                case 'country':
                    if (is_string($value) && !empty($value)) {
                        $required[$key] = true;
                    } else {
                        throw new \Exception("Field $key is invalid!", 1);
                    }
                    break;
                
                case 'partyName':
                    if (is_array($value) 
                        && !empty($value)
                        && !empty($value['en'])
                        ) {
                            $required[$key] = true;
                    } else {
                        throw new \Exception("Field $key is invalid!", 1);
                        
                    }
                    break;

                case 'type':
                    if (is_string($value) && !empty($value)) {
                        switch ($value) {
                            case 'national':
                                break;
                            case 'regional':
                            case 'local':
                            case 'youth':
                                if (empty($data['parentorganisation'])) {
                                    throw new \Exception("For this type of organisation field 'parentorganisation' is required", 1);
                                    
                                }
                                break;
                            
                            default:
                                throw new \Exception("Field $key is of an unknown value.", 1);
                                
                                break;
                        }
                        $required[$key] = true;
                    } else {
                        throw new \Exception("Field $key is invalid!", 1);
                    }
                    break;

                case 'partyCode':
                    if (is_string($value) && !empty($value)) {
                        if ($data['type'] == 'national') {
                            if (preg_match('/PP[A-Z]{2,3}/', $value) !== 1) {
                                throw new \Exception("$key is incorectly formated", 1);
                            }
                        } else {
                            if (preg_match('/PP[A-Z]{2,3}-.+/', $value) !== 1) {
                                throw new \Exception("$key is incorectly formated", 1);
                            }
                        }
                        $required[$key] = true;
                    } else {
                        throw new \Exception("Field $key is invalid!", 1);
                    }
                    break;



                default:
                    # code...
                    break;
            }
        }
        if (($f = array_search(false, $required)) !== false) {
            throw new \Exception("Not all required fields are present. Missing $f", 1);
            
        }
    }
        
}