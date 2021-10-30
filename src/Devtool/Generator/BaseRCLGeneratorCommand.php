<?php
declare(strict_types=1);


namespace Qingpizi\HyperfFramework\Devtool\Generator;


use Hyperf\Utils\CodeGen\Project;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

class BaseRCLGeneratorCommand extends Command
{
    /**
     * @var InputInterface
     */
    protected $input;

    /**
     * @var OutputInterface
     */
    protected $output;

    public function configure()
    {
        foreach ($this->getArguments() as $argument) {
            $this->addArgument(...$argument);
        }

        foreach ($this->getOptions() as $option) {
            $this->addOption(...$option);
        }
    }

    /**
     * Get the console command options.
     *
     * @return array
     */
    protected function getOptions(): array
    {
        return [
            ['force', 'f', InputOption::VALUE_NONE, 'Whether force to rewrite.'],
        ];
    }

    /**
     * Get the console command arguments.
     *
     * @return array
     */
    protected function getArguments(): array
    {
        return [
            ['name', InputArgument::REQUIRED, 'The name of the class'],
        ];
    }

    /**
     * Get the destination class path.
     *
     * @param string $name
     * @return string
     */
    protected function getPath(string $name): string
    {
        $project = new Project();
        return BASE_PATH . '/' . $project->path($name);
    }

    /**
     * Build the directory for the class if necessary.
     *
     * @param string $path
     * @return string
     */
    protected function makeDirectory($path)
    {
        if (! is_dir(dirname($path))) {
            mkdir(dirname($path), 0777, true);
        }

        return $path;
    }

    /**
     * Determine if the class already exists.
     *
     * @param string $name
     * @return bool
     */
    protected function alreadyExists($name): bool
    {
        return is_file($this->getPath($name));
    }

    /**
     * Execute the console command.
     *
     * @param InputInterface $input
     * @param OutputInterface $output
     * @param array $moduleNames
     * @param string|null $actionName
     * @return int
     */
    protected function executeHandle(InputInterface $input, OutputInterface $output, array $moduleNames, string $actionName = null): int
    {
        $modelName = ucfirst(trim($input->getArgument('model_name')));
        if (is_null($actionName)) {
            $actionName = ucfirst(trim($input->getArgument('action_name')));
        }
        foreach ($moduleNames as $moduleName) {
            $name = $this->getNamespace($moduleName, $modelName, $actionName);
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

    protected function getNamespace($moduleName, $modelName, $actionName): string
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
