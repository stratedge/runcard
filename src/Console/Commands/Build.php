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

        $includes = [];
        $middleware = [];
        $routes = [];

        /*
         * BUILD REQUIRE AND INCLUDE STATEMENTS
         */
        foreach(['require_once', 'require', 'include_once', 'include'] as $command) {
            if (!empty($data[$command])) {
                $parts = [];
                foreach ($data[$command] as $part) {
                    $parts[] = "$command '$part';";
                };
                $includes[] = implode("\n", $parts);
            }
        }

        /*
         * BUILD APPLICATION MIDDLEWARE
         */
        if (!empty($data['middleware'])) {
            $mw = [];
            foreach ($data['middleware'] as $middleware_data) {
                $obj = Factory::createMiddleware($middleware_data);
                $mw[] = '$app' . $obj . ';';
            }
            $middleware[] = implode("\n", $mw);
        }

        /*
         * BUILD ROUTES AND GROUPS
         */
        foreach ($data['routes'] as $route_data) {
            $obj = Factory::create($route_data);
            $routes[] = (string) '$app' . $obj;
        }

        $output = [];

        if (!empty($includes)) {
            $output[] = "/**\n * INCLUDES\n */\n" . implode("\n", $includes);
        }

        if (!empty($middleware)) {
            $output[] = "/**\n * APPLICATION MIDDLEWARE\n */\n" . implode("\n", $middleware);
        }

        if (!empty($routes)) {
            $output[] = "/**\n * ROUTES\n */\n" . implode("\n\n", $routes);
        }

        if (!empty($data['output'])) {
            $path = $data['output'];
        } else {
            $path = $dir . DIRECTORY_SEPARATOR . 'routes.php';
        } 

        file_put_contents($path, "<?php\n\n" . implode("\n\n\n", $output) . "\n");
    }
}
