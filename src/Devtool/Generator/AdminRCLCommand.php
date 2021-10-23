<?php
declare(strict_types=1);
/**
 * Created by PhpStorm
 * User: qingpizi
 * Date: 2021/10/10
 * Time: 下午3:26
 */

namespace Qingpizi\HyperfFramework\Devtool\Generator;

use Hyperf\Command\Annotation\Command;
use Hyperf\Devtool\Generator\GeneratorCommand;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * @Command
 */
#[Command]
class AdminRCLCommand extends GeneratorCommand
{

    public function __construct()
    {
        parent::__construct('easy:a-rcl');
        $this->addOption('exclude-class', 'e', InputOption::VALUE_IS_ARRAY | InputOption::VALUE_OPTIONAL, 'exclude class');
        $this->setDescription('Create new request class and new controller class and new logic class');
    }

    protected function getArguments()
    {
        return [
            ['group', InputArgument::REQUIRED, 'The grouping'],
        ];
    }

    /**
     * Execute the console command.
     *
     * @param InputInterface $input
     * @param OutputInterface $output
     * @return int
     */
    public function execute(InputInterface $input, OutputInterface $output)
    {
        $group = ucfirst(trim($input->getArgument('group')));
        $excludeClasses = $input->getOption('exclude-class');

        $classes = ['index', 'show', 'store', 'update', 'destroy'];
        if (! is_null($excludeClasses)) {
            foreach ($excludeClasses as $excludeClass) {
                if(! in_array($excludeClass, $classes)) {
                    $output->writeln(sprintf('<error>%s</error>', $excludeClass . ' not in [\'index\', \'show\', \'store\', \'update\', \'destroy\'].'));
                    return 0;
                }
            }
            $classes = array_diff($classes, $excludeClasses);
        }

        foreach ($classes as $class) {
            $class = ucfirst($class);
            $handleList = ['ValidationRequest', 'Controller', 'Logic'];
            foreach ($handleList as $handle) {
                $getNamespace = "get{$handle}Namespace";
                $buildController = "build{$handle}Class";
                $name = $this->$getNamespace($group, $class);
                $path = $this->getPath($name);
                if (($input->getOption('force') === false) && $this->alreadyExists($name)) {
                    $output->writeln(sprintf('<fg=red>%s</>', $name . ' already exists!'));
                    return 0;
                }
                $this->makeDirectory($path);

                file_put_contents($path, $this->$buildController($group, $class));

                $output->writeln(sprintf('<info>%s</info>', $name . ' created successfully.'));
            }
        }

        return 0;
    }

    /**
     * @param $group
     * @param $class
     * @return string
     */
    protected function getControllerNamespace($group, $class)
    {
        return 'App\\Controller' . '\\' . $group . '\\' . $this->getControllerClass($class);
    }

    protected function getControllerClass($class)
    {
        return $class . 'Controller';
    }

    protected function buildControllerClass($group, $class)
    {
        $stub = file_get_contents(__DIR__ . '/stubs/controller.stub');
        return str_replace(
            [
                '%GROUP%',
                '%CONTROLLER%',
                '%VALIDATION_REQUEST%',
                '%LOGIC%',
            ],
            [
                $group,
                $this->getControllerClass($class),
                $this->getValidationRequestClass($class),
                $this->getLogicClass($class),
            ],
            $stub
        );
    }

    /**
     * @param $group
     * @param $class
     * @return string
     */
    protected function getValidationRequestNamespace($group, $class)
    {
        return 'App\\Request' . '\\' . $group . '\\' . $this->getValidationRequestClass($class);
    }

    protected function getValidationRequestClass($class)
    {
        return $class . 'Request';
    }

    protected function buildValidationRequestClass($group, $class)
    {
        $stub = file_get_contents(__DIR__ . '/stubs/validation-request.stub');
        return str_replace(
            [
                '%GROUP%',
                '%VALIDATION_REQUEST%',
            ],
            [
                $group,
                $this->getValidationRequestClass($class),
            ],
            $stub
        );
    }

    /**
     * @param $group
     * @param $class
     * @return string
     */
    protected function getLogicNamespace($group, $class)
    {
        return 'App\\Logic' . '\\' . $group . '\\' . $this->getLogicClass($class);
    }

    protected function getLogicClass($class)
    {
        return $class . 'Logic';
    }

    protected function buildLogicClass($group, $class)
    {
        $stub = file_get_contents(__DIR__ . '/stubs/logic.stub');
        return str_replace(
            [
                '%GROUP%',
                '%LOGIC%',
            ],
            [
                $group,
                $this->getLogicClass($class),
            ],
            $stub
        );
    }

    /**
     * Get the desired class name from the input.
     *
     * @return string
     */
    protected function getGroupInput()
    {
        return trim($this->input->getArgument('group'));
    }

    /**
     * Get the desired class name from the input.
     *
     * @return string
     */
    protected function getClassInput()
    {
        return trim($this->input->getArgument('class'));
    }



    /**
     * Determine if the class already exists.
     *
     * @param string $name
     * @return bool
     */
    protected function alreadyExists($name)
    {
        return is_file($this->getPath($name));
    }

    /**
     * Get the stub file for the generator.
     */
    protected function getStub():string{
        return '';
    }

    /**
     * Get the default namespace for the class.
     */
    protected function getDefaultNamespace(): string {
        return '';
    }
}
