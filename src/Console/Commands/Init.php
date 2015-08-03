<?php
namespace Stratedge\Runcard\Console\Commands;

use Exception;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Yaml\Parser;

class Init extends Command
{
    protected function configure()
    {
        $this->setName('init')
             ->setDescription('Creates a route configuration file');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $dir = getcwd();
        $file = 'runcard.yml';

        $path = $dir . DIRECTORY_SEPARATOR . $file;

        if (is_file($path)) {
            throw new Exception('Runcard already appears to be initialized');
        }

        $default = <<<"EOT"
routes:
- uri: /
  method: get

EOT;

        file_put_contents($path, $default);
        
        $output->writeln('<info>Runcard has been initialized</info>');
    }
}
