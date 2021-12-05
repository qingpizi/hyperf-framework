<?php
declare(strict_types=1);

namespace Qingpizi\HyperfFramework\Devtool\Generator;

use Hyperf\Command\Annotation\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * @Command
 */
#[Command]
class CLCommand extends BaseRCLGeneratorCommand
{

    protected string $currentDiv = __DIR__;

    public function __construct()
    {
        parent::__construct('easy:cl');
        $this->addArgument('behavior_name', InputArgument::REQUIRED, 'The behavior name of the class');
        $this->setDescription('Create new controller&logic class');
    }

    /**
     * Execute the console command.
     *
     * @param InputInterface $input
     * @param OutputInterface $output
     * @return int
     */
    public function execute(InputInterface $input, OutputInterface $output): int
    {
        return $this->executeHandle($input, $output, ['controller', 'logic']);
    }

    protected function buildClass($moduleName, $groupName, $behaviorName)
    {
        $stub = file_get_contents($this->currentDiv . '/stubs/cl/' . $moduleName . '.stub');
        return str_replace(
            [
                '%GROUP_NAME%',
                '%BEHAVIOR_NAME%',
            ],
            [
                $groupName,
                $behaviorName,
            ],
            $stub
        );
    }
}
