<?php
namespace Stratedge\Runcard\Console\Commands;

use Stratedge\Runcard\Factory;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Yaml\Parser;

class Build extends Command
{
    protected function configure()
    {
        $this->setName('build')
             ->setDescription('Builds a route file from a route configuration file');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $yaml = new Parser();

        $dir = getcwd();
        $file = 'runcard.yml';

        $path = $dir . DIRECTORY_SEPARATOR . $file;

        $data = $yaml->parse(file_get_contents($path));

        $output = [];

        /*
         * BUILD REQUIRE AND INCLUDE STATEMENTS
         */
        foreach(['require_once', 'require', 'include_once', 'include'] as $command) {
            if (!empty($data[$command])) {
                $parts = [];
                foreach ($data[$command] as $part) {
                    $parts[] = "$command '$part';";
                };
                $output[] = implode("\n", $parts);
            }
        }

        /*
         * BUILD APPLICATION MIDDLEWARE
         */
        if (!empty($data['middleware'])) {
            $middleware = [];
            foreach ($data['middleware'] as $middleware_data) {
                $obj = Factory::createMiddleware($middleware_data);
                $middleware[] = '$app' . $obj . ';';
            }
            $output[] = implode("\n", $middleware);
        }

        /*
         * BUILD ROUTES AND GROUPS
         */
        foreach ($data['routes'] as $route_data) {
            $obj = Factory::create($route_data);
            $output[] = (string) $obj;
        }

        $path = $dir . DIRECTORY_SEPARATOR . 'routes.php';

        file_put_contents($path, "<?php\n\n" . implode("\n\n", $output) . "\n");
    }
}
