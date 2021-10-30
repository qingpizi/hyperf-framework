<?php
declare(strict_types=1);


namespace Qingpizi\HyperfFramework\Devtool\Generator;


use Hyperf\Utils\CodeGen\Project;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

class BaseRCLGeneratorCommand extends BaseGeneratorCommand
{


    /**
     * Get the console command arguments.
     *
     * @return array
     */
    protected function getArguments()
    {
        return [
            ['model_name', InputArgument::REQUIRED, 'The model name of the group'],
            ['action_name', InputArgument::OPTIONAL, 'The action name of the class'],
        ];
    }

    /**
     * Execute the console command.
     *
     * @param InputInterface $input
     * @param OutputInterface $output
     * @return int
     */
    protected function executeHandle(InputInterface $input, OutputInterface $output, array $moduleNames, string $actionName = null)
    {
        $modelName = ucfirst(trim($input->getArgument('model_name')));
        if (is_null($actionName)) {
            $actionName = ucfirst(trim($input->getArgument('action_name')));
        }
        foreach ($moduleNames as $moduleName) {
            $name = $this->getNamespace($modelName, $moduleName, $actionName);
            $path = $this->getPath($name);
            if (($input->getOption('force') === false) && $this->alreadyExists($name)) {
                $output->writeln(sprintf('<fg=red>%s</>', $name . ' already exists!'));
                return 0;
            }
            $this->makeDirectory($path);

            file_put_contents($path, $this->buildClass($modelName, $moduleName, $actionName));

            $output->writeln(sprintf('<info>%s</info>', $name . ' created successfully.'));
        }

        return 0;
    }


    protected function getNamespace($modelName, $moduleName, $actionName): string
    {
        return 'App\\' .ucfirst($moduleName) . '\\' . ucfirst($modelName) . '\\' . ucfirst($actionName) . ucfirst($moduleName);
    }

    protected function buildClass($modelName, $moduleName, $actionName)
    {
        $stub = file_get_contents($this->currentDiv . '/stubs/'. $actionName .'/' . $moduleName . '.stub');
        return str_replace(
            [
                '%MODEL_NAME%',
            ],
            [
                $modelName,
            ],
            $stub
        );
    }
}
